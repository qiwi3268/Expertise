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
//	3  - Произошла внутрення ошибка 'ShellException'
//       Произошла внутрення ошибка 'PregMatchException'
//       Произошла внутрення ошибка 'CSPMessageParserException'
//       Произошла внутрення ошибка 'CSPValidatorException'
//       {result, error_message : текст ошибки}
//  4  - Произошла непредусмотренная ошибка при работе метода '\csp\Validator::validate'
//       {result, message : текст ошибки, code: код ошибки}
//  5  - Произошла ошибка при парсинге fs_name_data / fs_name_sign
//       {result, message : текст ошибки, code: код ошибки}
//  6  - Произошла ошибка при добавлении записи в таблицу подписей
//       {result, message : текст ошибки, code: код ошибки}
//  7  - Все прошло успешно
//       {result, error_message : текст ошибки}
//  8  - Непредвиденная ошибка
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
    
    $Parser = new \csp\MessageParser(true);
    $Shell = new \csp\ExternalSignature();
    $Validator = new \csp\Validator($Parser, $Shell);
    
    try{
        
        $validateResults = $Validator->validate($P_fs_name_data, $P_fs_name_sign);
    }catch(ShellException $e){
        
        // Shell:exec
        // Исполняемая команда: не произвела вывод или произошла ошибка
        $date = $Logger->write($e->getMessage());
        exit(json_encode(['result'        => 3,
                          'error_message' => "Произошла внутрення ошибка 'ShellException'. log time: '{$date}'"
        ]));
    }catch(PregMatchException $e){
        
        // GetHandlePregMatch
        // Произошла ошибка или нет вхождений шаблона при работе функции GetHandlePregMatch
        $date = $Logger->write($e->getMessage());
        exit(json_encode(['result'        => 3,
                          'error_message' => "Произошла внутрення ошибка 'PregMatchException'. log time: '{$date}'"
        ]));
    }catch(CSPMessageParserException $e){
        
        // MessageParser::getFIO
        // code:
        //  1 - в БД не нашлось имени из ФИО
        //  2 - в одном Signer нашлось больше одного ФИО
        $date = $Logger->write($e->getMessage());
        $code = $e->getCode();
        exit(json_encode(['result'        => 3,
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
        exit(json_encode(['result'        => 3,
                          'error_message' => "Произошла внутрення ошибка 'CSPValidatorException'. code: '{$code}'. log time: '{$date}'"
        ]));
    }catch(Exception $e){
    
        $errorMessage = $e->getMessage();
        $errorCode = $e->getCode();
        $Logger->write("Произошла непредвиденная ошибка при работе метода '\csp\Validator::validate'. Message: '{$errorMessage}', Code: '{$errorCode}'");
        exit(json_encode(['result'  => 4,
                          'message' => $errorMessage,
                          'code'	=> $errorCode
        ]));
    }
    
    try{
    
        // Получение id файлов
        list('file_name' => $hash_data) = ParseHelper::parseApplicationFilePath($P_fs_name_data);
        list('file_name' => $hash_sign) = ParseHelper::parseApplicationFilePath($P_fs_name_sign);
    }catch(PregMatchException $e){
        
        // Произошла ошибка при парсинге P_fs_name_data / P_fs_name_sign
        $errorMessage = $e->getMessage();
        $Logger->write($errorMessage);
        exit(json_encode(['result'        => 5,
                          'message'       => $errorMessage,
                          'code'	      => $e->getCode()
        ]));
    }
    
    $FileClassName = $Mapping->getFileClassName();
    
    // *** Опускаем проверку на null по причине предшествующего API_file_checker
    $id_data = $FileClassName::getIdByHash($hash_data);
    $id_sign = $FileClassName::getIdByHash($hash_sign);
    
    $ClassName = $Mapping->getClassName();
    
    // Создаем запись в таблице подписей
    foreach($validateResults as &$result){
    
    
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
        }catch(Exception $e){
    
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();
            $Logger->write("Произошла ошибка при добавлении записи в таблицу подписей: '{$ClassName}'. Message: '{$errorMessage}', Code: '{$errorCode}'");
            exit(json_encode(['result'  => 6,
                              'message' => $e->getMessage(),
                              'code'	=> $e->getCode()
            ]));
        }
        
        unset($result['signature_verify']['message']);
        unset($result['certificate_verify']['message']);
    }
    unset($result);
    
    // Все прошло успешно
    exit(json_encode(['result'           => 7,
                      'validate_results' => $validateResults
    ]));
}catch(Exception $e){
    
    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
    $Logger->write("Произошла непредвиденная ошибка. Message: '{$errorMessage}', Code: '{$errorCode}'");
    exit(json_encode(['result'  => 8,
                      'message' => $errorMessage,
                      'code'	=> $errorCode
    ]));
}