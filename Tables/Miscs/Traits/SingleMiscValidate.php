<?php


namespace Tables\Miscs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Miscs\Interfaces\SingleMiscValidate}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait SingleMiscValidate
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Miscs\Interfaces\SingleMiscValidate::checkExistById()}
     *
     * @param int $id
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistById(int $id): bool
    {
        $table = self::$tableName;

        $query = "SELECT count(*)>0
                  FROM `{$table}`
                  WHERE `id`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }
}

