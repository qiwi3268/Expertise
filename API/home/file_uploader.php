<?php


// API предназначен для загрузке файлов в контексте заявления
//
// API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//  2  - todo забронировано под проверку на доступ к заявлению
//  3  - Отсутствуют загруженные файлы
//       {result, error_message : текст ошибки}
//  4  - Произошли ошибки при загрузке файлов на сервер
//       {result, error_message : текст ошибки, error = [{file_name : имя файла, error_text: текст ошибки},...]}
//  5  - Не пройдены проверки на допустимые форматы файлов
//       {result, error_message : текст ошибки, error = [имя файла, ...]}
//  6  - Не пройдены проверки на запрещенные символы
//       {result, error_message : текст ошибки, error = [имя файла, ...]}
//  7  - Не пройдены проверки на максимально допустимый размер файлов
//       {result, error_message : текст ошибки, error = [имя файла, ...]}
//  8  - Запрашиваемого маппинга не существует
//       {result, error_message : текст ошибки}
//  9  - Указанного в маппинге класса не существует
//       {result, error_message : текст ошибки}
//  10 - Указанный в маппинге класс не реализует требуемый интерфейс
//       {result, error_message : текст ошибки}
//  11 - Возникла ошибка при добавлении записи файла в таблицу, НО получилось удалить (или их не было) ранее созданные записи
//       {result, message : текст ошибки, code: код ошибки}
//  12 - Возникла ошибка при добавлении записи файла в таблицу, и НЕ получилось удалить ранее созданные записи
//       {result, message : текст ошибки, code: код ошибки}
//  13 - Возникли ошибки при переносе загруженного файла в указанную директорию, и НЕ получилось удалить часть из успешно загруженных
//       {result, error_message : текст ошибки, error = [имя файла, ...]}
//  14 - Возникли ошибки при переносе загруженного файла в указанную директорию, НО получилось удалить (или их не было) успешно загруженные
//       {result, error_message : текст ошибки}
//  15 - Загрузка файлов прошла успешно, НО не получилось обновить флаги в таблице
//       {result, error_message : текст ошибки}
//  16 - Все операции прошли усешно
//       {result, uploaded_files = [{id : id записи в таблице, name : имя файла, hash : хэш файла},...]}
//  17 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}
//




// Проверка наличия обязательных параметров
if(!checkParamsPOST('id_application', 'mapping_level_1', 'mapping_level_2')){
    
    exit_missingParamsPOST();
// Проверка наличия параметра id_structure_node для 2-го уровня _FILE_TABLE_MAPPING
}elseif($_POST['mapping_level_1'] == 2 && !checkParamsPOST('id_structure_node')){
    
    exit_missingParamsPOST();
}

function exit_missingParamsPOST(){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}


try{

    /** @var string $P_id_application    */
    /** @var string $P_mapping_level_1   */
    /** @var string $P_mapping_level_2   */
    /** @var string $P_id_structure_node */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    // Проверка заявителя на доступ к загрузке файлов в указанное заявление
    if(Session::isApplicant()){
        //TODO для заявителя необходимо реализовать проверку, что он имеет право получать документы из указанного заявления
        //exit result 2
    }

    $files = new FilesUpload($_FILES);

    if(!$files->checkFilesExist()){
        exit(json_encode(['result'        => 3,
                          'error_message' => 'Отсутствуют загруженные файлы'
                         ]));
    }

    // Ошибки при загрузке файлов на сервер
    if(!$files->checkServerUploadErrors()){

        $errorArr = [];

        foreach($files->getErrors() as $error){

            $errorArr[] = ['file_name'  => $error['name'],
                           'error_text' => $error['error']
                          ];
        }

        exit(json_encode(['result'        => 4,
                          'error_message' => 'Произошли ошибки при загрузке файлов на сервер',
                          'error'         => $errorArr
                         ]));
    }

    // Блок проверок файлов для заявителя
    if(Session::isApplicant()){

        // Допустимые форматы файлов
        $allowedFormats = ['.docx', '.doc', '.odt', '.pdf', '.xlsx', '.xls', '.ods', '.xml'];

        if(!$files->checkFilesName($allowedFormats, true)){

            $errorArr = $files->getErrors();
            exit(json_encode(['result'        => 5,
                              'error_message' => 'Не пройдены проверки на допустимые форматы файлов',
                              'error'         => $errorArr
                             ]));
        }

        // Запрещенные символы в файлах
        $forbiddenSymbols = [','];

        if(!$files->checkFilesName($forbiddenSymbols, false)){

            $errorArr = $files->getErrors();
            exit(json_encode(['result'        => 6,
                              'error_message' => 'Не пройдены проверки на запрещенные символы',
                              'error'         => $errorArr
                             ]));
        }

        // Максимальный размер файлов
        $maxFileSize = 80;

        if(!$files->checkMaxFilesSize($maxFileSize)){

            $errorArr = $files->getErrors();
            exit(json_encode(['result'        => 7,
                              'error_message' => 'Не пройдены проверки на максимально допустимый размер файлов',
                              'error'         => $errorArr
                             ]));
        }
    }

    // Блок проверки маппинга
    $Mapping = new FilesTableMapping($P_mapping_level_1, $P_mapping_level_2);
    $mappingErrorCode = $Mapping->getErrorCode();

    if(!is_null($mappingErrorCode)){

        $errorMessage = $Mapping->getErrorText();

        switch($mappingErrorCode){

            case 1:
                exit(json_encode(['result' => 8, 'error_message' => $errorMessage]));
                break;
            case 2:
                exit(json_encode(['result' => 9, 'error_message' => $errorMessage]));
                break;
            case 3:
                exit(json_encode(['result' => 10, 'error_message' => $errorMessage]));
                break;
        }
    }

    $Class = $Mapping->getClassName();

    // Блок генерации уникального хэша
    $applicationDir = _APPLICATIONS_FILES_.'/'.$P_id_application.'/';
    $inputName = 'download_files';
    $filesCount = $files->getFilesCount($inputName);

    $hashes = [];
    $uniqueHashCount = 0;

    do{

        $hash = bin2hex(random_bytes(40)); // Длина 80 символов
        if(!file_exists($applicationDir.$hash)){

            $hashes[] = $hash;
            $uniqueHashCount++;
        }
    }while($uniqueHashCount != $filesCount);

    // Блок создания записей в указанной таблице
    $createdIds = []; // id успешно созданных записей в таблице файлов
    $filesName = $files->getFilesName($inputName);
    $filesSize = $files->getFilesSize($inputName);
    

    // Формируем первую (переменную) часть параметров для передачи в метод создания записи.
    // Эти параметры будут распакованы и первыми переданы в сооветствующие методы,
    // таким образом, вторая часть принимаемых параметров должна быть идентичная
    if($P_mapping_level_1 == 1)      $params = [$P_id_application];
    elseif($P_mapping_level_1 == 2)  $params = [$P_id_application, $P_id_structure_node];

    for($s = 0; $s < $filesCount; $s++){

        try{

            $createdIds[] = call_user_func_array([$Class, 'create'], [...$params, $filesName[$s], $filesSize[$s], $hashes[$s]]);
        }catch(DataBaseException $e){

            // При добавлении записи файла произошла ошибка, удаляем все предыдущие записи
            try{

                foreach($createdIds as $id){
                    $Class::deleteById($id);
                }

                exit(json_encode(['result'  => 11,
                                  'message' => $e->getMessage(),
                                  'code'    => $e->getCode()
                                 ]));
            }catch(DataBaseException $ex){

                // Возникла ошибка при попытке удалить созданные записи файлов
                // (самый плохой исход из всех возможных)
                exit(json_encode(['result'  => 12,
                                  'message' => $ex->getMessage(),
                                  'code'    => $ex->getCode()
                                 ]));
            }
        }
    }

    // Загрузка файлов в указанну директорию
    if(!$files->uploadFiles($inputName, $applicationDir, $hashes)){

        // Массив успешно загруженных файлов
        $successfullyUpload = array_diff($filesName, $files->getErrors());

        $errorArr = [];

        // Если часть файлов загрузилась - пробуем их удалить
        if(count($successfullyUpload) > 0){

            foreach($successfullyUpload as $file){

                // Имеются только имена успешно загруженных файлов. Находим их хэш
                $fileIndex = array_search($file, $filesName, true);

                $hash = $hashes[$fileIndex];

                // Не получилось удалить файл
                if($fileIndex === false || !unlink($applicationDir.$hash)){
                    $errorArr[] = $file;
                }
            }
        }

        // Есть файлы, которые не получилось удалить
        if($errorArr){

            exit(json_encode(['result'        => 13,
                              'error_message' => 'Возникли ошибки при переносе загруженного файла в указанную директорию, и НЕ получилось удалить часть из успешно загруженных',
                              'error'         => $errorArr
                             ]));
        }else{
            exit(json_encode(['result'        => 14,
                              'error_message' => 'Возникли ошибки при переносе загруженного файла в указанную директорию, НО получилось удалить (или их не было) успешно загруженные'
                             ]));
        }
    }

    // Загрузка файлов прошла успешно, обновляем флаги в таблице файлов
    try{

        foreach($createdIds as $id){
            $Class::setUploadedById($id);
        }
    }catch(DataBaseException $e){

        exit(json_encode(['result'        => 15,
                          'error_message' => 'Загрузка файлов прошла успешно, НО не получилось обновить флаги в таблице'
                         ]));
    }

    // Формирование выходного результата. Все операции прошли успешно
    $uploadedFiles = [];

    for($s = 0; $s < $filesCount; $s++){

        $uploadedFiles[] = [
            'id'   => $createdIds[$s],
            'name' => $filesName[$s],
            'hash' => $hashes[$s]
        ];
    }

    exit(json_encode(['result'         => 16,
                      'uploaded_files' => $uploadedFiles
                     ]));

}catch(Exception $e){

    exit(json_encode(['result'  => 17,
                      'message' => $e->getMessage(),
                      'code'	=> $e->getCode()
                     ]));
}



