<?php


namespace Tables\Structures;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;


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
     * <i>is_active</i> = 1<br>
     * <i>id_341_main_block</i> IS NOT NULL
     *
     * @return array ассоциативные массивы структуры документации
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActiveAndId341NN(): array
    {
        $query = "SELECT *
                  FROM `structure_documentation_1`
                  WHERE `is_active`=1 AND `id_341_main_block` IS NOT NULL
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }
}