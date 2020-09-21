<?php


namespace Tables\Structures;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;


/**
 * Таблица: <i>'structure_documentation_2'</i>
 *
 */
class documentation_2
{

    /**
     * Предназначен для получения ассициативных массивов структуры документации
     *
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActive(): array
    {
        $query = "SELECT *
                  FROM `structure_documentation_2`
                  WHERE `is_active`=1
                  ORDER BY `sort`";
        return SimpleQuery::getFetchAssoc($query);
    }
}