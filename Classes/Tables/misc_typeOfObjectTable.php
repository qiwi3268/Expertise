<?php

// Справочник "Вид объекта"
//
final class misc_typeOfObjectTable{

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
