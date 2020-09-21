<?php


namespace Tables\Miscs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


/**
 * Предназначен для реализации общих методов одиночных справочников
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait SingleMisc
{

    /**
     * Предназначен для получения ассоциативных массивов активных справочников
     *
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActive(): array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `name`
                  FROM `{$table}`
                  WHERE `is_active`=1
                  ORDER BY `sort`";
        return SimpleQuery::getFetchAssoc($query);
    }


    /**
     * Предназначен для получения ассициативного массива справочника по его id
     *
     * @param int $id id записи справочника
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAssocById(int $id): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }
}

