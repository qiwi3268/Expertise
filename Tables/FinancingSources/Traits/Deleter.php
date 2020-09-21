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
     * Предназначен для удаления всех записей источников финансирования, относящихся к заявлению по его id
     *
     * @param int $id_application id заявления
     * @throws DataBaseEx
     */
    static public function deleteAllByIdApplication(int $id_application): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id_application`=?";
        ParametrizedQuery::set($query, [$id_application]);
    }
}
