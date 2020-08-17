<?php


// API предназначен для валидации открепленной подписи к файлу
// *** Предполагается, что перед использованием данного API был вызов API_file_checker для открепленной подписи и
//     для исходного файла, поскольку в данном API опускаются проверки: на доступ к файлам,  на их физическое существование, на корректность маппингов и т.д.
//
// API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//	2  - Ошибка в указанном маппинге таблицы подписей
//       {result, error_message : текст ошибки}
//  3  - Произошла ошибка при парсинге fs_name_data / fs_name_sign
//       {result, error_message : текст ошибки}
//  4  - id заявления исходного файла не равен id заявления файла подписи
//       {result, error_message : текст ошибки}
//	5  - Произошла внутрення ошибка 'ShellException'
//       Произошла внутрення ошибка 'PregMatchException'
//       Произошла внутрення ошибка 'CSPMessageParserException'
//       Произошла внутрення ошибка 'CSPValidatorException'
//       {result, error_message : текст ошибки}
//  6.1- Произошла внутрення ошибка (по вине входных данных):
//       Проверка открепленной подписи не началась (вместо открепленной подписи проверялся файл без подписи)
//       {result, error_message : текст ошибки}
//  7  - Произошла непредвиденная ошибка при работе метода '\csp\Validator::validate'
//       {result, message : текст ошибки, code: код ошибки}
//  8  - Произошла ошибка при добавлении записи в таблицу подписей
//       {result, message : текст ошибки, code: код ошибки}
//  9  - Все прошло успешно
//       {result, validate_results : массив результатов валидации}
//  10 - Ошибка при работе с Logger
//       {result, message : текст ошибки, code: код ошибки}
//  11 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}

// Проверка наличия обязательных параметров
if(!checkParamsPOST('fs_name_data', 'fs_name_sign', 'mapping_level_1', 'mapping_level_2')){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try{
    
    /** @var string $P_fs_name_data    */
    /** @var string $P_fs_name_sign    */
    /** @var string $P_mapping_level_1 */
    /** @var string $P_mapping_level_2 */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');
    
    $Logger = new Logger(_LOGS_.'/csp/errors', 'API_external_signature_verifier.log');
    
    // Блок проверки маппинга
    $Mapping = new SignsTableMapping($P_mapping_level_1, $P_mapping_level_2);
    
    if(!is_null($Mapping->getErrorCode())){
        
        $errorMessage = $Mapping->getErrorText();
        $Logger->write($errorMessage);
        exit(json_encode(['result'        => 2,
                          'error_message' => $errorMessage
        ]));
    }
    
    try{
        
        // Получение id файлов
        list('application_id' => $application_id, 'file_name' => $hash_data) = ParseHelper::parseApplicationFilePath($P_fs_name_data);
        list('application_id' => $tmp_id, 'file_name' => $hash_sign) = ParseHelper::parseApplicationFilePath($P_fs_name_sign);
    }catch(PregMatchException $e){
        
        // Произошла ошибка при парсинге P_fs_name_data / P_fs_name_sign
        $errorMessage = $e->getMessage();
        $Logger->write($errorMessage);
        exit(json_encode(['result'        => 3,
                          'message'       => $errorMessage,
                          'error_message' => $e->getCode()
        ]));
    }
    
    // Проверка на то, что id исходного файла и файла подписи относятся к одному заявлению
    if($application_id != $tmp_id){
        
        $errorMessage = "id заявления исходного файла: '{$application_id}' не равен id заявления файла подписи: '{$tmp_id}'";
        $Logger->write($errorMessage);
        exit(json_encode(['result'        => 4,
                          'error_message' => $errorMessage,
        ]));
    }
    
    $FileClassName = $Mapping->getFileClassName();
    
    // ***Опускаем проверку на null по причине предшествующего API_file_checker
    $dataFileAssoc = $FileClassName::getAssocByIdApplicationAndHash($application_id, $hash_data);
    $signFileAssoc = $FileClassName::getAssocByIdApplicationAndHash($application_id, $hash_sign);
    
    
    $Parser = new \csp\MessageParser(true);
    $Shell = new \csp\ExternalSignature();
    $Validator = new \csp\Validator($Parser, $Shell);
    
    try{
        
        $validateResults = $Validator->validate($P_fs_name_data, $P_fs_name_sign);
    }catch(ShellException $e){
        
        // Shell:exec
        // Исполняемая команда: не произвела вывод или произошла ошибка
        $date = $Logger->write($e->getMessage());
        exit(json_encode(['result'        => 5,
                          'error_message' => "Произошла внутрення ошибка 'ShellException'. log time: '{$date}'"
        ]));
    }catch(PregMatchException $e){
        
        // GetHandlePregMatch
        // Произошла ошибка или нет вхождений шаблона при работе функции GetHandlePregMatch
        $date = $Logger->write($e->getMessage());
        exit(json_encode(['result'        => 5,
                          'error_message' => "Произошла внутрення ошибка 'PregMatchException'. log time: '{$date}'"
        ]));
    }catch(CSPMessageParserException $e){
        
        // MessageParser::getFIO
        // code:
        //  1 - в БД не нашлось имени из ФИО
        //  2 - в одном Signer нашлось больше одного ФИО
        $date = $Logger->write($e->getMessage());
        $code = $e->getCode();
        exit(json_encode(['result'        => 5,
                          'error_message' => "Произошла внутрення ошибка 'CSPMessageParserException'. code: '{$code}'. log time: '{$date}'"
        ]));
    }catch(CSPValidatorException $e){
        
        // Validator::validate
        // code:
        //  1 - получен неизвестный результат проверки подписи / сертификата (подписи)
        //  2 - неизвестный формат блока, следующий за Signer
        //  3 - неизвестная часть сообщения
        //  4 - в частях сообщения отсустсвует(ют) Signer
        //  5 - получено некорректное количество блоков ErrorCode
        //  6 - в результате проверки БЕЗ цепочки сертификатов не был найден подписант из результатов проверки С цепочкой сертификатов
        $date = $Logger->write($e->getMessage());
        $code = $e->getCode();
    
        // В частях сообщения отсутствует(ют) Signer
    
        // Последняя ошибка связана с тем, что проверка подписи не началась
        // Для открепленной подписи ошибка означает:
        //    - проверяется файл без подписи и файл без подписи
        if($code == 4 && $Validator->isSignatureVerifyingNotStarted()){
            exit(json_encode(['result'        => 6.1,
                              'error_message' => "Проверка открепленной подписи не началась. code: '{$code}'. log time: '{$date}'"
            ]));
        }
        
        exit(json_encode(['result'        => 5,
                          'error_message' => "Произошла внутрення ошибка 'CSPValidatorException'. code: '{$code}'. log time: '{$date}'"
        ]));
    }catch(Exception $e){
    
        $errorMessage = $e->getMessage();
        $errorCode = $e->getCode();
        $Logger->write("Произошла непредвиденная ошибка при работе метода '\csp\Validator::validate'. Message: '{$errorMessage}', Code: '{$errorCode}'");
        exit(json_encode(['result'  => 7,
                          'message' => $errorMessage,
                          'code'	=> $errorCode
        ]));
    }
    
    $ClassName = $Mapping->getClassName();
    $id_data = $dataFileAssoc['id'];
    $id_sign = $signFileAssoc['id'];
    
    // Создаем запись в таблице подписей
    foreach($validateResults as &$result){
    
        // Изменяем сообщение для пользователя, если файл размером больше 20 КБ
        if($result['signature_verify']['user_message'] == 'Подпись не соответствует файлу' && ($signFileAssoc['file_size'] / 1024 > 20)){
            $result['signature_verify']['user_message'] = 'Подпись не соответствует файлу. Вероятно, была загружена встроенная подпись вместо открепленной.';
        }
        
        try{
    
            $ClassName::create($id_sign,
                               1,
                               $id_data,
                               $result['fio'],
                               $result['certificate'],
                               $result['signature_verify']['result'] ? 1 : 0,
                               $result['signature_verify']['message'],
                               $result['signature_verify']['user_message'],
                               $result['certificate_verify']['result'] ? 1 : 0,
                               $result['certificate_verify']['message'],
                               $result['certificate_verify']['user_message']);
        }catch(DataBaseException $e){
    
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();
            $Logger->write("Произошла ошибка при добавлении записи в таблицу подписей: '{$ClassName}'. Message: '{$errorMessage}', Code: '{$errorCode}'");
            exit(json_encode(['result'  => 8,
                              'message' => $e->getMessage(),
                              'code'	=> $e->getCode()
            ]));
        }
        
        unset($result['signature_verify']['message']);
        unset($result['certificate_verify']['message']);
    }
    unset($result);
    
    // Все прошло успешно
    exit(json_encode(['result'           => 9,
                      'validate_results' => $validateResults
    ]));
    
}catch(LoggerException $e){
    
    exit(json_encode(['result'  => 10,
                      'message' => $e->getMessage(),
                      'code'	=> $e->getCode()
    ]));
}catch(Exception $e){
    
    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
    $Logger->write("Произошла непредвиденная ошибка. Message: '{$errorMessage}', Code: '{$errorCode}'");
    exit(json_encode(['result'  => 11,
                      'message' => $errorMessage,
                      'code'	=> $errorCode
    ]));
}