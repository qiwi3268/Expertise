<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;


final class user_role
{

    // Предназначен для создания записи роли пользователя
    // Принимает параметры-----------------------------------
    // Все параметры, согласно таблице `user_role`
    // Возвращает параметры-----------------------------------
    // id int : id созданной записи
    //
    //todo среднее - посмотреть что возвращает. id записи в этой таблице нет
    static public function create(int $id_user, int $id_user_info_role): int
    {
        $query = "INSERT INTO `user_role`
                    (`id_user`, `id_user_info_role`)
                  VALUES
                    (?, ?)";
        return ParametrizedQuery::set($query, [$id_user, $id_user_info_role]);
    }
}