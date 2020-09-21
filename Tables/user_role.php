<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'user_role'</i>
 *
 */
final class user_role
{

    /**
     * Предназначен для создания записи роли пользователя
     *
     * @param int $id_user id пользователя
     * @param int $id_user_info_role id роли
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_user, int $id_user_info_role): int
    {
        $query = "INSERT INTO `user_role`
                    (`id_user`, `id_user_info_role`)
                  VALUES
                    (?, ?)";
        return ParametrizedQuery::set($query, [$id_user, $id_user_info_role]);
    }
}