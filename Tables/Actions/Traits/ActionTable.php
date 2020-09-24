<?php


namespace Tables\Actions\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


/**
 * Частично реализует интерфейс {@see \Tables\Actions\Interfaces\ActionTable}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait ActionTable
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Actions\Interfaces\ActionTable::getAllAssocWhereActive()}
     *
     * @return array
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActive(): array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `page_name`,
                         `name`,
                         `callback_name`,
                         `description`
                  FROM `{$table}`
                  WHERE `is_active`=1
                  ORDER BY `sort`";
        return SimpleQuery::getFetchAssoc($query);
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Actions\Interfaces\ActionTable::getAssocWhereActiveByPageName()}
     *
     * @param string $pageName
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getAssocWhereActiveByPageName(string $pageName): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `page_name`,
                         `name`,
                         `callback_name`,
                         `description`
                  FROM `{$table}`
                  WHERE `page_name`=? AND`is_active`=1";
        $result = ParametrizedQuery::getFetchAssoc($query, [$pageName]);
        return $result ? $result[0] : null;
    }
}


