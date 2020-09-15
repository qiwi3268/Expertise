<?php


namespace Tables\Actions;

use Lib\DataBase\ParametrizedQuery;


// Действия над типом документа - Заявление
//
final class application implements Interfaces\ActionTable
{
    static private string $tableName = 'action_application';

    use Traits\ActionTable;


    // Предназначен для получения ассоциативного массива данных бизнесс-процесса, необходимых для работы callback-методов
    // Принимает параметры-----------------------------------
    // id_application int : id заявления
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив данных бизнесс-процесса
    //
    static public function getAssocBusinessProcessById(int $id): array
    {
        $query = "SELECT `doc_application`.`is_saved`,
                         `doc_application`.`id_stage`
                  FROM `doc_application`
                  WHERE `doc_application`.`id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id])[0];
    }
}