<?php


namespace Tables\Structures;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;


/**
 * Таблица: <i>'structure_documentation_1'</i>
 *
 */
class documentation_1
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
                  FROM `structure_documentation_1`
                  WHERE `is_active`=1
                  ORDER BY `sort`";
        return SimpleQuery::getFetchAssoc($query);
    }


    /**
     * Предназначен для получения ассоциативных массивов стркутуры документации
     *
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActiveAndId341NN(): array
    {
        $query = "SELECT *
                  FROM `structure_documentation_1`
                  WHERE `is_active`=1 AND `id_main_block_341` IS NOT NULL
                  ORDER BY `sort`";
        return SimpleQuery::getFetchAssoc($query);
    }
}