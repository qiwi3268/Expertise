<?php


namespace Tables\Miscs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Miscs\Interfaces\SingleMisc}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait SingleMisc
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Miscs\Interfaces\SingleMisc::getAllAssocWhereActive()}
     *
     * @return array
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
     * Реализация метода интерфейса
     * {@see \Tables\Miscs\Interfaces\SingleMisc::getAssocById()}
     *
     * @param int $id
     * @return array|null
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

