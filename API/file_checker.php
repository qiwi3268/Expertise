<?php


// API предназначен для проверки возможности выгрузить указанный файл в констекте заявления
//
//API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//  2  - todo забронировано под проверку на доступ к заявлению
//  3  - Запрашиваемого маппинга не существует
//       {result, error_message : текст ошибки}
//  4  - Указанного в маппинге класса не существует
//       {result, error_message : текст ошибки}
//  5  - Указанный в маппинге класс не реализует требуемый интерфейс
//       {result, error_message : текст ошибки}
//  6  - Запрашиваемой записи файла не существует в БД
//       {result, error_message : текст ошибки}
//  7  - У запрашиваемой записи файла в БД не проставлен флаг загрузки на сервер
//       {result, error_message : текст ошибки}
//  8  - Файл физически отсутствует на сервере
//       {result, error_message : текст ошибки}
//  9  - Все проверки прошли успешно
//       {result, fs_name, file_name}
//       * fs_name - полный путь к файлу на сервере
//  10 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}

// Проверка наличия обязательных параметров
if(!checkParamsPOST(_PROPERTY_IN_APPLICATION['id_application'], 'id_file', 'mapping_level_1', 'mapping_level_2')){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
                     ]));
}

try{


    /** @var string $P_id_application  */
    /** @var string $P_id_file         */
    /** @var string $P_mapping_level_1 */
    /** @var string $P_mapping_level_2 */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    // Проверка заявителя на доступ к заявлению
    if(Session::isApplicant()){
        //TODO для заявителя необходимо реализовать проверку, что он имеет право получать документы из указанного заявления
        //exit result 2
    }

    // Блок проверки маппинга
    $mapping = new FilesTableMapping($P_mapping_level_1, $P_mapping_level_2);

    $mappingError = $mapping->getError();

    if(!is_null($mappingError)){

        $errorMessage = $mapping->getErrorText();

        switch($mappingError){

            case 1:
                exit(json_encode(['result' => 3, 'error_message' => $errorMessage]));
                break;
            case 2:
                exit(json_encode(['result' => 4, 'error_message' => $errorMessage]));
                break;
            case 3:
                exit(json_encode(['result' => 5, 'error_message' => $errorMessage]));
                break;
        }
    }

    $Class = $mapping->getClassName();

    $fileAssoc = $Class::getAssocById($P_id_file);

    // Проверка на существование записи в таблице
    if(is_null($fileAssoc)){
        exit(json_encode(['result'        => 6,
                          'error_message' => 'Запрашиваемой записи файла не существует в БД'
                         ]));
    }

    // Проверка на успешную загрузку файла на сервер
    if($fileAssoc['is_uploaded'] == 0){
        exit(json_encode(['result'        => 7,
                          'error_message' => 'У запрашиваемой записи файла в БД не проставлен флаг загрузки на сервер'
                         ]));
    }

    $applicationDir = _APPLICATIONS_FILES_.'/'.$P_id_application.'/';
    $pathToFile = $applicationDir.$fileAssoc['hash'];

    // Проверка файла на физическое существование
    if(!file_exists($pathToFile)){
        exit(json_encode(['result'        => 8,
                          'error_message' => 'Файл физически отсутствует на сервере'
                         ]));
    }

    // Все проверки прошли успешно
    exit(json_encode(['result'    => 9,
                      'fs_name'   => $pathToFile,
                      'file_name' => $fileAssoc['file_name']
                     ]));
}catch(Exception $e){

    exit(json_encode(['result'  => 10,
                      'message' => $e->getMessage(),
                      'code'	=> $e->getCode()
                     ]));
}










