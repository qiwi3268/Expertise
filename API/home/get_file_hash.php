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
if(!checkParamsPOST('sign_algorithm', 'id_file', 'mapping_level_1', 'mapping_level_2')){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try{
    
    /** @var string $P_sign_algorithm */
    /** @var string $P_id_file */
    /** @var string $P_mapping_level_1 */
    /** @var string $P_mapping_level_2 */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');
    
    // Проверка заявителя на доступ к заявлению не нужна, т.к. производится на предыдущем этапе - в file_checker
    // Блок проверки маппинга - не нужен, т.к. производится на предыдущем этапе - в file_checker
    
    // Проверка существования указанного алгоритма попдписи
    if(!isset(sign_algorithms[$P_sign_algorithm])){
        exit(json_encode(['result'        => 2,
                          'error_message' => "Получен неопределенный алгоритм подписи: '{$P_sign_algorithm}'"
        ]));
    }
    
    $Mapping = new FilesTableMapping($P_mapping_level_1, $P_mapping_level_2);
    $Class = $Mapping->getClassName();
    
    $fileAssoc = $Class::getAssocById($P_id_file);
    
    $applicationId = $fileAssoc['id_application'];
    $applicationDir = _APPLICATIONS_FILES_."/{$applicationId}";
    
    $filePath = "{$applicationDir}/{$fileAssoc['hash']}";
    
    // Содержимое исходного файла
    $data = file_get_contents($filePath);
    
    if($data === false){
        exit(json_encode(['result'        => 3,
                         'error_message' => "Произошла ошибка при чтении исходного файла: '{$filePath}'"
        ]));
    }
    
    $base64_data = base64_encode($data);
    $base64_fileName = null;
    
    // Формирование имени для временного base64 файла
    do{
        
        $hash = bin2hex(random_bytes(10)); // Длина 20 символов
        if(!file_exists(_TMP_BASE64_FILES_."/{$hash}")) $base64_fileName = $hash;
        
    }while(!$base64_fileName);
    
    
    $base64_filePath = _TMP_BASE64_FILES_."/{$base64_fileName}";
    
    // Создание временного base64 файла
    if(file_put_contents($base64_filePath, $base64_data) === false){
        exit(json_encode(['result'        => 4,
                          'error_message' => "Произошла ошибка при создании временного base64 файла: '{$base64_filePath}'"
        ]));
    }
    
    // Получаем алгоритм хэширования на основе алгоритма подписи
    $hashAlgorithm = hash_algorithms[$P_sign_algorithm];
    
    // todo Вынести в отдельный класс
    $cmd = sprintf('/opt/cprocsp/bin/amd64/cryptcp -hash -dir "%s" -provtype 80 -hashAlg "%s" "%s" 2>&1', _TMP_HASH_FILES_, $hashAlgorithm, $base64_filePath);
    
    // todo Проверить на ошибки
    $message = shell_exec($cmd);
    
    // Путь к созданному файлу hash'а (с расширением .hsh)
    $hash_filePath = _TMP_HASH_FILES_."/{$base64_fileName}.hsh";
    
    $hash_data = file_get_contents($hash_filePath);
    
    if($hash_data === false){
        exit(json_encode(['result'        => 6,
                          'error_message' => "Произошла ошибка при чтении созданного hash файла: '{$hash_filePath}'"
        ]));
    }
    
    // Удаляем временный base64 файл
    if(!unlink($base64_filePath)){
        exit(json_encode(['result'        => 7,
                          'error_message' => "Произошла ошибка при удалении временного base64 файла: '{$base64_filePath}'"
        ]));
    }
    
    // Удаляем временный hash файл
    if(!unlink($hash_filePath)){
        exit(json_encode(['result'        => 8,
                          'error_message' => "Произошла ошибка при удалении временного hash файла: '{$hash_filePath}'"
        ]));
    }
    
    // Все прошло успешно
    exit(json_encode(['result'    => 9,
                      'fs_name'   => $filePath,
                      'file_name' => $fileAssoc['file_name']
    ]));
    
    
}catch(Exception $e){
    
    exit(json_encode(['result'  => 10,
                      'message' => $e->getMessage(),
                      'code'	=> $e->getCode()
    ]));
}