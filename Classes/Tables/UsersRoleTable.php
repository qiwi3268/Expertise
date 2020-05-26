<?php

final class UsersRoleTable{

    // Предназначен для создания записи роли пользователя
    // Принимает параметры-----------------------------------
    // Все параметры, согласно таблице `users_role`
    // Возвращает параметры-----------------------------------
    // id int : id созданной записи
    //
    static public function create(int $id_user, int $role):int {

        $query = "INSERT INTO `users_role`
                    (`id_user`, `role`)
                  VALUES
                    (?, ?)";

        return ParametrizedQuery::set($query, [$id_user, $role]);
    }

}