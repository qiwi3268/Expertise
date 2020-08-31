<?php


namespace Tables\Structures;

use Lib\DataBase\SimpleQuery;


class documentation_2
{

    // Предназначен для получения ассициативного массива структуры документации,
    // возвращает активные данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : структура документации
    //
    static public function getAllActive(): array
    {
        $query = "SELECT *
                  FROM `structure_documentation_2`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }
}