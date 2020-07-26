<?php

// Трейт, предназначений для реализации общих методов действий
//
trait Trait_actionTable{

    // Предназначен для получения ассициативного массива дейсивий,
    // возвращает активные записи
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : ассоциативный массив действий
    //
    static public function getAllActive():array {

        $table = self::$tableName;

        $query = "SELECT `id`,
                         `page_name`,
                         `name`,
                         `callback_name`,
                         `description`
                  FROM $table
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }
}


