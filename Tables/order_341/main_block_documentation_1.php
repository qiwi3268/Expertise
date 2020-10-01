<?php


namespace Tables\order_341;

use Lib\DataBase\ParametrizedQuery;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Таблица: <i>'main_block_341_documentation_1'</i>
 *
 */
final class main_block_documentation_1
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
        $condition = implode(', ', array_fill(0, count($ids), '?'));

        $query = "SELECT `id`,
                         `name`
                  FROM `main_block_341_documentation_1`
                  WHERE `id` NOT IN ({$condition})
                  ORDER BY `sort`";

        $result = ParametrizedQuery::getFetchAssoc($query, [...$ids]);
        return $result ? $result : null;
    }
}