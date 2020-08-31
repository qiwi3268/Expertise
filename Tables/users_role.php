<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;


final class users_role
{

    // Предназначен для создания записи роли пользователя
    // Принимает параметры-----------------------------------
    // Все параметры, согласно таблице `users_role`
    // Возвращает параметры-----------------------------------
    // id int : id созданной записи
    //
    //todo - посмотреть что возвращает. id записи в этой таблице нет
    static public function create(int $id_user, int $role): int
    {
        $query = "INSERT INTO `users_role`
                    (`id_user`, `role`)
                  VALUES
                    (?, ?)";

        return ParametrizedQuery::set($query, [$id_user, $role]);
    }

}