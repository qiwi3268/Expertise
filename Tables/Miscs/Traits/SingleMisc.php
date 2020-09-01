<?php


namespace Tables\Miscs\Traits;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


// Трейт, предназначений для реализации общих методов одиночных справочников
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait SingleMisc
{

    // Предназначен для получения ассициативных массивов активных справочников,
    // возвращает активные записи
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : ассоциативные массивы справочников
    //
    static public function getAllActive(): array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `name`
                  FROM `{$table}`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";
        return SimpleQuery::getFetchAssoc($query);
    }


    // Предназначен для получения ассициативного массива справочника по его id
    // Принимает параметры------------------------------------
    // int : id записи справочника
    // Возвращает параметры-----------------------------------
    // array : ассоциативный массив записи справочника
    // null  : запись не существует
    //
    static public function getAssocById(int $id): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }
}

