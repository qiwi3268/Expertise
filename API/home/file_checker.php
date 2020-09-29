<?php


use core\Classes\Session;
use Lib\Files\Mappings\FilesTableMapping;

// API предназначен для проверки возможности выгрузить указанный файл в констекте заявления
//
// API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//  2  - todo забронировано под проверку на доступ к заявлению
//  3  - Ошибка в указанном маппинге
//       {result, error_message : текст ошибки}
//  4  - Запрашиваемой записи файла не существует в БД
//       {result, error_message : текст ошибки}
//  5  - У запрашиваемой записи файла в БД не проставлен флаг загрузки на сервер
//       {result, error_message : текст ошибки}
//  6  - Файл физически отсутствует на сервере
//       {result, error_message : текст ошибки}
//  7  - Все прошло успешно
//       {result, fs_name, file_name}
//       * fs_name - полный путь к файлу на сервере
//  8  - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}

// Проверка наличия обязательных параметров
if (!checkParamsPOST('id_application', 'id_file', 'mapping_level_1', 'mapping_level_2')) {

    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try {

    /** @var string $P_id_application */
    /** @var string $P_id_file */
    /** @var string $P_mapping_level_1 */
    /** @var string $P_mapping_level_2 */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    // Проверка заявителя на доступ к заявлению
    if (Session::isApp()) {
        //TODO для заявителя необходимо реализовать проверку, что он имеет право получать документы из указанного заявления
        //exit result 2
    }

    // Блок проверки маппинга
    $mapping = new FilesTableMapping($P_mapping_level_1, $P_mapping_level_2);

    if (!is_null($mapping->getErrorCode())) {

        exit(json_encode([
            'result'        => 3,
            'error_message' => $mapping->getErrorText()
        ]));
    }

    $class = $mapping->getClassName();

    $fileAssoc = $class::getAssocById($P_id_file);

    // Проверка на существование записи в таблице
    if (is_null($fileAssoc)) {

        exit(json_encode([
            'result'        => 4,
            'error_message' => 'Запрашиваемой записи файла не существует в БД'
        ]));
    }

    // Проверка на успешную загрузку файла на сервер
    if ($fileAssoc['is_uploaded'] == 0) {

        exit(json_encode([
            'result'        => 5,
            'error_message' => 'У запрашиваемой записи файла в БД не проставлен флаг загрузки на сервер'
        ]));
    }

    $applicationDir = APPLICATIONS_FILES . "/{$P_id_application}";
    $filePath = "{$applicationDir}/{$fileAssoc['hash']}";

    // Проверка файла на физическое существование
    if (!file_exists($filePath)) {

        exit(json_encode([
            'result'        => 6,
            'error_message' => 'Файл физически отсутствует на сервере'
        ]));
    }

    // Все прошло успешно
    exit(json_encode([
        'result'    => 7,
        'fs_name'   => $filePath,
        'file_name' => $fileAssoc['file_name']
    ]));

// Непредвиденная ошибка
} catch (Exception $e) {

    exit(json_encode([
        'result'  => 8,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}