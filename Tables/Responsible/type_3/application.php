<?php


namespace Tables\Responsible\type_3;

use Lib\DataBase\ParametrizedQuery;


// Ответственные группы заявителей к заявлению
//
final class application
{

    static private string $tableName = 'resp_application_type_3';


    // Предназначен для создания записи в таблице ответственных групп заявителей к заявлению
    //
    static public function createFullAccess(int $id_main_document, int $id_user): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `id_user`, `group_type`)
                  VALUES
                    (?, ?, 'full_access')";
        return ParametrizedQuery::set($query, [$id_main_document, $id_user]);
    }
}



