<?php


namespace Tables\Miscs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Miscs\Interfaces\DependentMiscValidate}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>corrTableName</i> с именем таблицы
 * корреляции главного и зависимого справочника
 *
 */
trait DependentMiscValidate
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Miscs\Interfaces\DependentMiscValidate::checkExistCorrByIds()}
     *
     * @param int $id_main
     * @param int $id_dependent
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistCorrByIds(int $id_main, int $id_dependent): bool
    {
        $table = self::$corrTableName;

        $query = "SELECT count(*)>0
                  FROM `{$table}`
                  WHERE `id_main`=? AND `id_dependent`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_main, $id_dependent])[0];
    }
}