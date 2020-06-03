<?php

// Справочник "Вид объекта"
//
final class misc_typeOfObjectTable implements Interface_miscTableValidate{

    static private string $tableName = 'misc_type_of_object';

    use Trait_miscTableValidate;

    // Предназначен для получения ассициативного массива "Вид объекта",
    // возвращает активные данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : цели экспертизы
    //
    static public function getAllActive():array {

        $query = "SELECT `id`,
                         `name`
                  FROM `misc_type_of_object`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }
}
