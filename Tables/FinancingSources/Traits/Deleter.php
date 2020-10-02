<?php


namespace Tables\FinancingSources\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait Deleter
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\FinancingSources\Interfaces\FinancingSourceTable::deleteAllByIdMainDocument()}
     *
     * @param int $id_main_document
     * @throws DataBaseEx
     */
    static public function deleteAllByIdMainDocument(int $id_main_document): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id_main_document`=?";
        ParametrizedQuery::set($query, [$id_main_document]);
    }
}
