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


    /**
     * Предназначен для проверки существования записи по id заявления и id пользователя
     *
     * Фактически метод предназначен для проверки присутствия пользователя в любой из групп к заявлению
     *
     * @param int $id_application id заявления
     * @param int $id_user id пользователя
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     * @throws DataBaseEx
     */
    static public function checkExistByIdApplicationAndIdUser(int $id_application, int $id_user): bool
    {
        $query = "SELECT COUNT(*)>0
                  FROM `applicant_access_group`
                  WHERE `id_application`=? AND `id_user`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_application, $id_user])[0];
    }
}