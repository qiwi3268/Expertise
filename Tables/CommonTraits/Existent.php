<?php


namespace Tables\CommonTraits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\CommonInterfaces\Existent}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait Existent
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\CommonInterfaces\Existent::checkExistById()}
     *
     * @param int $id
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistById(int $id): bool
    {
        $table = self::$tableName;

        $query = "SELECT COUNT(*)>0
                  FROM `{$table}`
                  WHERE `id`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }
}

