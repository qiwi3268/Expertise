<?php


// API предназначен для получения криптографического hash'а к требуемому файлу (для дальнейшего подписания на клиенсткой стороне)
// *** Предполагается, что перед использованием данного API был вызов API_file_checker, поскольку в данном
//     API опускаются проверки: на доступ к файлу, на корректность маппингов, на его физическое существование.
//
// API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//  2  - Получен неопределенный алгоритм подписи
//       {result, error_message : текст ошибки}
//  3  - Произошла ошибка при при чтении исходного файла
//       {result, error_message : текст ошибки}
//  4  - Произошла ошибка при создании base-64 файла
//       {result, error_message : текст ошибки}
//  todo - произошла ошибка при хэшировании фала
//  6  - Произошла ошибка при чтении созданного base-64 файла
//       {result, error_message : текст ошибки}
//  7  - Произошла ошибка при удалении временного base64 файла
//       {result, error_message : текст ошибки}
//  8  - Произошла ошибка при удалении временного hash файла
//       {result, error_message : текст ошибки}
//  9  - Все прошло успешно
//       {result, error_message : текст ошибки}
//  10 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}

// Проверка наличия обязательных параметров
if(!checkParamsPOST('sign_algorithm', 'fs_name')){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try{
    
    /** @var string $P_sign_algorithm */
    /** @var string $P_fs_name */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');
    
    // Проверка заявителя на доступ к заявлению не нужна, т.к. производится на предыдущем этапе - в file_checker
    // Блок проверки маппинга - не нужен, т.к. производится на предыдущем этапе - в file_checker
    
    // Проверка существования указанного алгоритма попдписи
    if(!isset(sign_algorithms[$P_sign_algorithm])){
        exit(json_encode(['result'        => 2,
                          'error_message' => "Получен неопределенный алгоритм подписи: '{$P_sign_algorithm}'"
        ]));
    }
    
    try{
        
        list('application_id' => $applicationId, 'file_name' => $fileName) = ParseHelper::parseApplicationFilePath($P_fs_name);
    }catch(PregMatchException $e){
    
        // Произошла ошибка при парсинге P_fs_name
        exit(json_encode(['result'  => 'todo',
                          'message' => $e->getMessage(),
                          'code'	=> $e->getCode()
        ]));
    }
    
    
    
    // Получаем алгоритм хэширования на основе алгоритма подписи
    $hashAlgorithm = hash_algorithms[$P_sign_algorithm];
    
    $FileHash = new \csp\FileHash();
    try{
        // Выполняем команду, на основе которой сгенерируется hash-файл
        $message = $FileHash->execHash(_TMP_HASH_FILES_, $hashAlgorithm, $P_fs_name);
        unset($FileHash);
    }catch(ShellException $e){
        
        exit(json_encode(['result'  => 'todo',
                          'message' => $e->getMessage(),
                          'code'	=> $e->getCode()
        ]));
    }
    
    // Проверка вывода исполняемой команды
    $MessageParser = new \csp\MessageParser(false);
    
    try{
        
        $errorCode = $MessageParser->getErrorCode($message);
    }catch(PregMatchException $e){
        
        // Произошла ошибка или нет вхождений ErrorCode
        exit(json_encode(['result'  => 'todo',
                          'message' => $e->getMessage(),
                          'code'	=> $e->getCode()
        ]));
    }
    
    // ErrorCode не соответствует
    if($errorCode != $MessageParser::ok_error_code){
        exit(json_encode(['result'        => 'todo',
                          'error_message' => "Исполняемая команда по получению hash-файла завершилась с ошибкой. [ErrorCode: {$errorCode}]"
        ]));
    }
    
    // Путь к созданному hash-файлу (с расширением .hsh)
    $hash_filePath = _TMP_HASH_FILES_."/{$fileName}.hsh";
    
    $hash_data = file_get_contents($hash_filePath);
    
    if($hash_data === false){
        exit(json_encode(['result'        => 6,
                          'error_message' => "Произошла ошибка при чтении созданного hash файла: '{$hash_filePath}'"
        ]));
    }
    
    // Удаляем временный hash-файл
    if(!unlink($hash_filePath)) exit(json_encode(['result'  => 8, 'error_message' => "Произошла ошибка при удалении временного hash файла: '{$hash_filePath}'"]));
    
    // Все прошло успешно
    exit(json_encode(['result' => 9,
                      'hash'   => $hash_data
    ]));
    
}catch(Exception $e){
    
    exit(json_encode(['result'  => 10,
                      'message' => $e->getMessage(),
                      'code'	=> $e->getCode()
    ]));
}