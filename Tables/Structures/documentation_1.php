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

    // Предназначен для получения ассициативных массивов структуры документации,
    // возвращает активные данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : ассоциативные массивы структуры документации
    //
    static public function getAllActive(): array
    {
        $query = "SELECT *
                  FROM `structure_documentation_1`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }


    /**
     * Предназначен для получения ассоциативных массивов стркутуры документации
     *
     * Возвращает данные по возрастанию столбца <i>sort</i>
     *
     * @return array ассоциативные массивы структуры документации
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