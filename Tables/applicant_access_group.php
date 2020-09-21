<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'applicant_access_group'</i>
 *
 */
class applicant_access_group
{

    /**
     * Предназначен для создания записи с пользователем в группу "Полный доступ" к заявлению
     *
     * @param int $id_application id заявления
     * @param int $id_user id пользователя
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function createFullAccess(int $id_application, int $id_user): int
    {
        $query = "INSERT INTO `applicant_access_group`
                    (`id`, `id_application`, `id_user`, `id_applicant_access_group_type`)
                  VALUES
                    (NULL, ?, ?, 1)";
        return ParametrizedQuery::set($query, [$id_application, $id_user]);
    }
}