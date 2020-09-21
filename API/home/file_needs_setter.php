<?php

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;

use core\Classes\Session;
use Lib\Files\Mappings\FilesTableMapping;
use Lib\DataBase\Transaction;
use Lib\Singles\PrimitiveValidator;

// API предназначен для установки флага 'is_needs' файла в контексте заявления
//
// API result:
//  1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//	2  - Ошибка при парсинге входного json'а
//       {result, error_message : текст ошибки}
//	3  - Ошибка при валидации входного json'а
//       {result, error_message : текст ошибки}
//	4  - Переданые пустые массивы to_save и to_delete
//       {result, error_message : текст ошибки}
//  5  - todo забронировано под проверку на доступ к заявлению
//	6  - Ошибка в указанном маппинге
//       {result, error_message : текст ошибки}
//	7  - Запись указанного файла в БД не существует
//       {result, error_message : текст ошибки}
//  8  - Ошибка при обновлении данных в БД
//       {result, message : текст ошибки, code: код ошибки}
//  9  - Все прошло успешно
//       {result}
//  10  - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}
//

if (!checkParamsPOST('id_application', 'file_needs_json')) {

    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try {

    /** @var string $P_id_application */
    /** @var string $P_file_needs_json */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    $primitiveValidator = new PrimitiveValidator();

    // Валидация json'а
    try {

        $fileNeedsAssoc = $primitiveValidator->getAssocArrayFromJson($P_file_needs_json, 4);
    } catch (PrimitiveValidatorEx $e) {

        exit(json_encode([
            'result'        => 2,
            'error_message' => $e->getMessage()
        ]));
    }

    // Валидация входного json'а
    try {

        $primitiveValidator->validateAssociativeArray($fileNeedsAssoc, [
            'to_save'   => ['is_array'],
            'to_delete' => ['is_array']
        ]);
    } catch (PrimitiveValidatorEx $e) {

        exit(json_encode([
            'result'        => 3,
            'error_message' => $e->getMessage()
        ]));
    }

    // Валидация массивов с файлами
    foreach ($fileNeedsAssoc as $typeName => $type) {

        foreach ($type as $index => $file) {

            try {

                $primitiveValidator->validateAssociativeArray($file, [
                    'id_file'         => ['is_int'],
                    'mapping_level_1' => ['is_int'],
                    'mapping_level_2' => ['is_int']
                ]);
            } catch (PrimitiveValidatorEx $e) {

                exit(json_encode([
                    'result'        => 3,
                    'error_message' => $e->getMessage()
                ]));
            }
        }
    }

    // Проверка на то, что в одном из массивов есть файлы
    if (empty($fileNeedsAssoc['to_save']) && empty($fileNeedsAssoc['to_delete'])) {
        
        exit(json_encode([
            'result'        => 4,
            'error_message' => 'Переданые пустые массивы to_save и to_delete'
        ]));
    }

    // Проверка заявителя на доступ к сохранению (установке флагов) файлов в указанное заявление
    if (Session::isApplicant()) {
        //TODO для заявителя необходимо реализовать проверку, что он имеет право получать документы из указанного заявления
        //exit result 5
    }

    // Проверка указанных маппингов на корректность
    // + запись свойства 'class_name' каждому файлу, для использования в дальнейшем
    // + проверка существования записи файла
    foreach ($fileNeedsAssoc as &$type) {

        foreach ($type as &$file) {

            $mapping = new FilesTableMapping($file['mapping_level_1'], $file['mapping_level_2']);

            if (!is_null($mapping->getErrorCode())) {

                exit(json_encode([
                    'result'        => 6,
                    'error_message' => $mapping->getErrorText()
                ]));
            }

            $className = $mapping->getClassName();
            $file['class_name'] = $className;

            // Проверка существования записи указанного файла
            if (!$className::checkExistById($file['id_file'])) {

                exit(json_encode([
                    'result'        => 7,
                    'error_message' => "Файл id: {$file['id_file']} таблицы класса: '{$className}' не существует"
                ]));
            }
            unset($mapping);
        }
        unset($file);
    }
    unset($type);

    $transaction = new Transaction();

    // Заполняем транзакцию
    // Сначала ставим метку ненужности, потом нужности. В случае, если на стороне клиентского js будет ошибка - файл останется "нужным"
    foreach ($fileNeedsAssoc['to_delete'] as $file) {
        $transaction->add($file['class_name'], 'setNeedsToFalseById', [$file['id_file']]);
    }
    foreach ($fileNeedsAssoc['to_save'] as $file) {
        $transaction->add($file['class_name'], 'setNeedsToTrueById', [$file['id_file']]);
    }

    try {

        $transaction->start();
    } catch (DataBaseEx $e) {

        exit(json_encode([
            'result'  => 8,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }

    // Все прошло успешно
    exit(json_encode(['result' => 9]));

} catch (Exception $e) {

    exit(json_encode([
        'result'  => 10,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}