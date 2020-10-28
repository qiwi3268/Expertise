<?php


namespace Tables\Structures\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;



/**
 * Реализует общие методы для таблиц структуры документации
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait  StructureTable
{

    /**
     * Предназначен для получения ассициативных массивов структуры документации
     *
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActive(): array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `is_active`=1
                  ORDER BY `sort`";
        return SimpleQuery::getFetchAssoc($query);
    }


    /**
     * Предназначен для получения ассоциативных массивов стркутуры документации,
     * которые привязаны к блоку из 341 приказа
     *
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActiveAndId341NN(): array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `is_active`=1 AND `id_main_block_341` IS NOT NULL
                  ORDER BY `sort`";
        return SimpleQuery::getFetchAssoc($query);
    }
}