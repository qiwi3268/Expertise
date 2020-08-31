<?php


namespace Tables\Actions\Traits;

use Lib\DataBase\SimpleQuery;


// Трейт, предназначений для реализации общих методов действий
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait ActionTable
{

    // Предназначен для получения ассициативных массивов дейсивий,
    // возвращает активные записи
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : ассоциативные массивы действий
    //
    static public function getAllActive(): array
    {

        $table = self::$tableName;

        $query = "SELECT `id`,
                         `page_name`,
                         `name`,
                         `callback_name`,
                         `description`
                  FROM `{$table}`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }
}


