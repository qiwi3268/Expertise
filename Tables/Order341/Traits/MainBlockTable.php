<?php


namespace Tables\Order341\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;

use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Реализует общие методы для таблиц блока из 341 приказа
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait MainBlockTable
{

    /**
     * Предназначен для получения ассоциативных массивов записей, id которых не влючены в переданный массив ids
     *
     * Возвращает данные по возрастанию столбца <i>sort</i>
     *
     * @param int[] $ids массив id
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereIdNotInIds(array $ids): ?array
    {
        $table = self::$tableName;
        $condition = TableHelper::getConditionForIN($ids);

        $query = "SELECT `id`,
                         `name`
                  FROM `{$table}`
                  WHERE `id` NOT IN ({$condition})
                  ORDER BY `sort`";

        $result = ParametrizedQuery::getFetchAssoc($query, [...$ids]);
        return $result ? $result : null;
    }
}

