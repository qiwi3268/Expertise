<?php


namespace Tables\Miscs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;


/**
 * Реализует интерфейс {@see \Tables\Miscs\Interfaces\DependentMisc}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName
 * - mainTableName
 * - corrTableName<br>
 * с именами соответсвующих таблиц
 *
 */
trait DependentMisc
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Miscs\Interfaces\DependentMisc::getAllAssocWhereActiveCorrMain()}
     *
     * @return array
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereActiveCorrMain(): array
    {
        $table = self::$tableName;
        $mainTable = self::$mainTableName;
        $corrTable = self::$corrTableName;

        $query = "SELECT `{$mainTable}`.`id` AS `id_main`,
                         `{$table}`.`id`,
                         `{$table}`.`name`
                  FROM `$corrTable` AS `corr`
                  INNER JOIN `{$mainTable}`
                          ON (`corr`.`id_main`=`{$mainTable}`.`id`)
                  INNER JOIN `{$table}`
                          ON (`corr`.`id_dependent`=`{$table}`.`id`)
                  WHERE `{$mainTable}`.`is_active`=1 AND `{$table}`.`is_active`=1
                  ORDER BY `{$table}`.`sort`";
        $result = SimpleQuery::getFetchAssoc($query);

        // Укладываем зависимый справочник по id главного справочника
        $arr = [];
        foreach ($result as ['id_main' => $id_main, 'id' => $id, 'name' => $name]) {
            $arr[$id_main][] = ['id' => $id, 'name' => $name];
        }
        return $arr;
    }
}