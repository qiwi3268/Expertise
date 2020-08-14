<?php


// API предназначен для установки флага 'is_needs' файла в контексте заявления
//
// API result:
//  1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//	2  - Ошибка при парсинге входного json'а
//       {result, message : текст ошибки, code: код ошибки}
//	3  - Ошибка при валидации входного json'а
//       {result, error_message : текст ошибки}
//	4  - Переданые пустые массивы to_save и to_delete
//       {result, error_message : текст ошибки}
//  5  - todo забронировано под проверку на доступ к заявлению
//	6  - Ошибка в указанном маппинге
//       {result, error_message : текст ошибки}
//	7  - Указанный файл не существует
//       {result, error_message : текст ошибки}
//  8  - Ошибка при обновлении данных в БД
//       {result, message : текст ошибки, code: код ошибки}
//  9  - Все операции прошли усешно
//       {result}
//  10  - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}
//

if(!checkParamsPOST('id_application', 'file_needs_json')){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try{

    /** @var string $P_id_application    */
    /** @var string $P_file_needs_json   */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    // Валидация json'а
    try{
        $fileNeedsAssoc = json_decode($P_file_needs_json, true, 4, JSON_THROW_ON_ERROR);
    }catch(jsonException $e){
        exit(json_encode(['result'  => 2,
                          'message' => $e->getMessage(),
                          'code'	=> $e->getCode()
        ]));
    }

    // Валидация входного json'а
    // Верхний уровень должен состоять только из элементов to_save и to_delete
    if((count($fileNeedsAssoc) != 2) ||  !array_key_exists('to_save', $fileNeedsAssoc) || !array_key_exists('to_delete', $fileNeedsAssoc)){
        exit(json_encode(['result'        => 3,
                          'error_message' => "Верхний уровень входного json'а имеет неверную структуру"
        ]));
    }

    // to_save и to_delete должны быть ключами массивов
    if(!is_array($fileNeedsAssoc['to_save']) || !is_array($fileNeedsAssoc['to_delete'])){
        exit(json_encode(['result'        => 3,
                          'error_message' => "Элемент to_save и(или) to_delete не являются ключами массива"
        ]));
    }

    // Массивы файлов должны быть индексными и содержать id_file, mapping_level_1 и mapping_level_2
    foreach($fileNeedsAssoc as $typeName => $type){

        foreach($type as $index => $file){

            if(!is_int($index) ||
               count($file) != 3 ||
               !array_key_exists('id_file', $file) ||
               !array_key_exists('mapping_level_1', $file) ||
               !array_key_exists('mapping_level_2', $file)){

                exit(json_encode(['result'        => 3,
                                  'error_message' => "Файл из раздела $typeName имеет неверную структуру"
                ]));
            }

            // Проверка на содержание только int'овых значений
            if(!is_int($file['id_file']) ||
               !is_int($file['mapping_level_1']) ||
               !is_int($file['mapping_level_2'])){

                exit(json_encode(['result'        => 3,
                                  'error_message' => "Файл из раздела $typeName имеет нечисловые значения в структуре"
                ]));
            }
        }
    }
    
    // Проверка на то, что в одном из массивов есть файлы
   if(empty($fileNeedsAssoc['to_save']) && empty($fileNeedsAssoc['to_delete'])){
       exit(json_encode(['result'        => 4,
                         'error_message' => 'Переданые пустые массивы to_save и to_delete'
       ]));
   }

    // Проверка заявителя на доступ к сохранению (установке флагов) файлов в указанное заявление
    if(Session::isApplicant()){
        //TODO для заявителя необходимо реализовать проверку, что он имеет право получать документы из указанного заявления
        //exit result 5
    }

    // Проверка указанных маппингов на корректность
    // + запись свойства 'class_name' каждому файлу, для использования в дальнейшем
    // + проверка существования файла
    foreach($fileNeedsAssoc as &$type){

        foreach($type as &$file){

            $Mapping = new FilesTableMapping($file['mapping_level_1'], $file['mapping_level_2']);

            $mappingErrorCode = $Mapping->getErrorCode();

            if(!is_null($mappingErrorCode)){

                exit(json_encode(['result'        => 6,
                                  'error_message' => $Mapping->getErrorText()
                ]));
            }

            $className = $Mapping->getClassName();
            $file['class_name'] = $className;

            // Проверка существования указанного файла
            if(!$className::checkExistById($file['id_file'])){

                exit(json_encode(['result'        => 7,
                                  'error_message' => "Файл id: {$file['id_file']} таблицы класса $className не существует"
                ]));
            }
            unset($Mapping);
        }
        unset($file);
    }
    unset($type);

    // Сначала ставим метку ненужности, потом нужности. В случае, если на стороне клиентского js будет ошибка - файл останется "нужным"
    try{

        foreach($fileNeedsAssoc['to_delete'] as $file) call_user_func_array([$file['class_name'], 'setIsNeedsToFalseById'], [$file['id_file']]);
        foreach($fileNeedsAssoc['to_save'] as $file) call_user_func_array([$file['class_name'], 'setIsNeedsToTrueById'], [$file['id_file']]);
    }catch(DataBaseException $e){

        exit(json_encode(['result'  => 8,
                          'message' => $e->getMessage(),
                          'code'	=> $e->getCode()
        ]));
    }

    // Успешное обновление данных
    exit(json_encode(['result' => 9]));

}catch(Exception $e){

    exit(json_encode(['result'  => 10,
                      'message' => $e->getMessage(),
                      'code'	=> $e->getCode()
    ]));
}
