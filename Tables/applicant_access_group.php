<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;


class applicant_access_group
{
    static public function createFullAccess(int $id_application, int $id_user): int
    {
        $query = "INSERT INTO `applicant_access_group`
                    (`id`, `id_application`, `id_user`, `id_applicant_access_group_type`)
                  VALUES
                    (NULL, ?, ?, 1)";
        return ParametrizedQuery::set($query, [$id_application, $id_user]);
    }
}