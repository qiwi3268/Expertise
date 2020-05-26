<?php


// Справочник "Цель обращения"
//
final class misc_expertisePurposeTable{

    // Предназначен для получения ассициативного массива целей экспертизы,
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : цели экспертизы
    //
    static public function getAllActive():array {

        $query = "SELECT `id`,
                         `name`
                  FROM `misc_expertise_purpose`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }
}
