<?php


namespace Tables\Miscs\Traits;

use Lib\DataBase\ParametrizedQuery;


// Трейт, реализующий интерфейс Tables\Miscs\Interfaces\SingleMiscValidate
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait SingleMiscValidate
{

    // Предназначен для проверки существования справочника по его id
    // Принимает параметры-----------------------------------
    // id int : id справочника
    // Возвращает параметры----------------------------------
    // true  : справочник существует
    // false : справочник не существует
    //
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

