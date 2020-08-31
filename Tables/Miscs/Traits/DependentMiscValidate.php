<?php


namespace Tables\Miscs\Traits;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


// Трейт, реализующий интерфейс Tables\Miscs\Interfaces\DependentMiscValidate
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статические свойства: tableName, mainTableName, corrTableName с именеми соответсвующих таблиц
//
trait DependentMiscValidate
{

    // Предназначен для проверки существования связи главного и зависимого справочника по их id
    // Принимает параметры-----------------------------------
    // id_main      int : id главного справочника
    // id_dependent int : id зависимого справочника
    // Возвращает параметры----------------------------------
    // true  : зависимость существует
    // false : зависимость не существует
    //
    static public function checkExistCorrByIds(int $id_main, int $id_dependent): bool
    {
        $table = self::$corrTableName;

        $query = "SELECT count(*)>0
                  FROM `{$table}`
                  WHERE `id_main`=? AND `id_dependent`=?";

        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_main, $id_dependent])[0];
    }


    // Предназначен для получения ассоциативного массива зависимого справочника, упакованного по id главного справочника
    // Возвращает параметры----------------------------------
    // array : индексный массив (id главного справочника), в элементах которого находятся ассоциативные массивы зависимого справочника
    //
    static public function getAllActiveCorrMain(): array
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
                  ORDER BY `{$table}`.`sort` ASC";

        $result = SimpleQuery::getFetchAssoc($query);

        // Укладываем зависимый справочник по id главного справочника
        $arr = [];
        foreach ($result as ['id_main' => $id_main, 'id' => $id, 'name' => $name]) {
            $arr[$id_main][] = ['id' => $id, 'name' => $name];
        }

        return $arr;
    }
}