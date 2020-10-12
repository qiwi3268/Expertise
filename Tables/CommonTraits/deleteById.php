<?php


namespace Tables\CommonTraits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует общий метод по удалению записи из таблицы
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait deleteById
{

    /**
     * Предназначен для удаления записи в таблице по ее id
     *
     * @param int $id id записи
     * @throws DataBaseEx
     */
    static public function deleteById(int $id): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }
}

