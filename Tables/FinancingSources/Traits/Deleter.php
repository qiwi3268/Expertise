<?php


namespace Tables\FinancingSources\Traits;

use Lib\DataBase\ParametrizedQuery;


// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait Deleter
{

    // Предназначен для удаления всех записей источников финансирования, относящихся к заявлению по его id
    // Принимает параметры-----------------------------------
    // id_application int : id заявления
    //
    static public function deleteAllByIdApplication(int $id_application): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id_application`=?";

        ParametrizedQuery::set($query, [$id_application]);
    }
}
