<?php


use Lib\Exceptions\DataBase as DataBaseEx;

use core\Classes\Session;
use Lib\Files\Uploader;
use Lib\Files\Mappings\FilesTableMapping;
use Lib\DataBase\Transaction;

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
//  8  - Ошибка в указанном маппинге
//       {result, error_message : текст ошибки}
//  9  - Возникла ошибка при создании записей файлов в таблицу
//       {result, message : текст ошибки, code: код ошибки}
//  10 - Возникли ошибки при переносе загруженного файла в указанную директорию, и НЕ получилось удалить часть из успешно загруженных
//       {result, error_message : текст ошибки, error = [имя файла, ...]}
//  11 - Возникли ошибки при переносе загруженного файла в указанную директорию, НО получилось удалить (или их не было) успешно загруженные
//       {result, error_message : текст ошибки}
//  12 - Загрузка файлов прошла успешно, НО не получилось обновить флаги загрузки файла на сервер в таблице
//       {result, message : текст ошибки, code: код ошибки}
//  13 - Все операции прошли усешно
//       {result, uploaded_files = [{id : id записи в таблице,
//                                   name : имя файла,
//                                   hash : хэш файла,
//                                   human_file_size : человекопонятный формат размера файла'},...]}
//  14 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}
//

// Проверка наличия обязательных параметров
if (
    !checkParamsPOST('id_application', 'mapping_level_1', 'mapping_level_2')
    || ($_POST['mapping_level_1'] == 2 && !checkParamsPOST('id_structure_node'))
) {
    
    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}


try {

    /** @var string $P_id_application */
    /** @var string $P_mapping_level_1 */
    /** @var string $P_mapping_level_2 */
    /** @var string $P_id_structure_node */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    // Проверка заявителя на доступ к загрузке файлов в указанное заявление
    if (Session::isApplicant()) {
        //TODO для заявителя необходимо реализовать проверку, что он имеет право получать документы из указанного заявления
        //exit result 2
    }

    $files = new Uploader($_FILES);

    if (!$files->checkFilesExist()) {
        
        exit(json_encode([
            'result'        => 3,
            'error_message' => 'Отсутствуют загруженные файлы'
        ]));
    }

    // Ошибки при загрузке файлов на сервер
    if (!$files->checkServerUploadErrors()) {

        $errorArr = [];

        foreach ($files->getErrors() as $error) {

            $errorArr[] = [
                'file_name'  => $error['name'],
                'error_text' => $error['error']
            ];
        }

        exit(json_encode([
            'result'        => 4,
            'error_message' => 'Произошли ошибки при загрузке файлов на сервер',
            'error'         => $errorArr
        ]));
    }

    // Блок проверок файлов для заявителя
    if (Session::isApplicant()) {

        // Допустимые форматы файлов
        $allowedFormats = ['.docx', '.doc', '.odt', '.pdf', '.xlsx', '.xls', '.ods', '.xml'];

        if (!$files->checkFilesName($allowedFormats, true)) {
            
            exit(json_encode([
                'result'        => 5,
                'error_message' => 'Не пройдены проверки на допустимые форматы файлов',
                'error'         => $files->getErrors()
            ]));
        }

        // Запрещенные символы в файлах
        $forbiddenSymbols = [','];

        if (!$files->checkFilesName($forbiddenSymbols, false)) {

            $errorArr = $files->getErrors();
            
            exit(json_encode([
                'result'        => 6,
                'error_message' => 'Не пройдены проверки на запрещенные символы',
                'error'         => $errorArr
            ]));
        }

        // Максимальный размер файлов
        $maxFileSize = 80;

        if (!$files->checkMaxFilesSize($maxFileSize)) {

            $errorArr = $files->getErrors();
            
            exit(json_encode([
                'result'        => 7,
                'error_message' => 'Не пройдены проверки на максимально допустимый размер файлов',
                'error'         => $errorArr
            ]));
        }
    }

    // Блок проверки маппинга
    $mapping = new FilesTableMapping($P_mapping_level_1, $P_mapping_level_2);

    if (!is_null($mapping->getErrorCode())) {

        exit(json_encode([
            'result'        => 8,
            'error_message' => $mapping->getErrorText()
        ]));
    }

    $class = $mapping->getClassName();

    // Блок генерации уникального хэша
    $applicationDir = APPLICATIONS_FILES . "/{$P_id_application}/";
    $inputName = 'download_files';
    $filesCount = $files->getFilesCount($inputName);

    $hashes = [];
    $uniqueHashCount = 0;

    do {

        $hash = bin2hex(random_bytes(40)); // Длина 80 символов
        if (!file_exists($applicationDir . $hash)) {

            $hashes[] = $hash;
            $uniqueHashCount++;
        }
    } while ($uniqueHashCount != $filesCount);

    // Блок создания записей в указанной таблице
    $createdIds = []; // id успешно созданных записей в таблице файлов
    $filesName = $files->getFilesName($inputName);
    $filesSize = $files->getFilesSize($inputName);


    // Формируем первую (переменную) часть параметров для передачи в метод создания записи.
    // Эти параметры будут распакованы и первыми переданы в сооветствующие методы,
    // таким образом, вторая часть принимаемых параметров должна быть идентичная
    $params = ($P_mapping_level_1 == 1) ? [$P_id_application] : [$P_id_application, $P_id_structure_node];

    $transaction = new Transaction();

    // Заполняем транзакцию создания записей файлов
    for ($l = 0; $l < $filesCount; $l++) {
        $transaction->add($class, 'create', [...$params, $filesName[$l], $filesSize[$l], $hashes[$l]]);
    }

    try {

        $createdIds = $transaction->start()->getLastResults()[$class]['create'];
    } catch (DataBaseEx $e) {

        exit(json_encode([
            'result'  => 9,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }

    // Загрузка файлов в указанну директорию
    if (!$files->uploadFiles($inputName, $applicationDir, $hashes)) {

        // Массив успешно загруженных файлов
        $successfullyUpload = array_diff($filesName, $files->getErrors());

        $errorArr = [];

        // Если часть файлов загрузилась - пробуем их удалить
        if (count($successfullyUpload) > 0) {

            foreach ($successfullyUpload as $file) {

                // Имеются только имена успешно загруженных файлов. Находим их хэш
                $fileIndex = array_search($file, $filesName, true);

                $hash = $hashes[$fileIndex];

                // Не получилось удалить файл
                if ($fileIndex === false || !unlink($applicationDir . $hash)) {
                    $errorArr[] = $file;
                }
            }
        }

        // Есть файлы, которые не получилось удалить
        if (!empty($errorArr)) {

            exit(json_encode([
                'result'        => 10,
                'error_message' => 'Возникли ошибки при переносе загруженного файла в указанную директорию, и НЕ получилось удалить часть из успешно загруженных',
                'error'         => $errorArr
            ]));
        } else {

            exit(json_encode([
                'result'        => 11,
                'error_message' => 'Возникли ошибки при переносе загруженного файла в указанную директорию, НО получилось удалить (или их не было) успешно загруженные'
            ]));
        }
    }

    // Заполняем транзакцию обновления флагов загрузки файла на сервер в таблице
    foreach ($createdIds as $id) $transaction->add($class, 'setUploadedById', [$id]);

    try {

        $transaction->start();
    } catch (DataBaseEx $e) {

        exit(json_encode([
            'result'  => 12,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }

    // Формирование выходного результата. Все прошло успешно
    $uploadedFiles = [];

    for ($l = 0; $l < $filesCount; $l++) {

        $uploadedFiles[] = [
            'id'              => $createdIds[$l],
            'name'            => $filesName[$l],
            'hash'            => $hashes[$l],
            'human_file_size' => getHumanFileSize($filesSize[$l])
        ];
    }

    exit(json_encode([
        'result'         => 13,
        'uploaded_files' => $uploadedFiles
    ]));

} catch (Exception $e) {

    exit(json_encode([
        'result'  => 14,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}