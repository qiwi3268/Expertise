<?php


namespace Tables\FinancingSources\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\FinancingSources\Interfaces\FinancingSourceTable}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait FinancingSourceTable
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\FinancingSources\Interfaces\FinancingSourceTable::getAllAssocByIdApplication()}
     *
     * @param int $id_application
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getAllAssocByIdApplication(int $id_application): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `id_application`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_application]);
        return $result ? $result : null;
    }
}
