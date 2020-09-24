<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Lib\DataBase\SimpleQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Таблица: <i>'user'</i>
 *
 */
final class user
{

    /**
     * Предназначен для создания записи пользователя
     *
     * @param string $last_name
     * @param string $first_name
     * @param string $middle_name
     * @param int|null $id_user_info_department для заявителя NULL
     * @param int|null $id_user_info_position для заявителя NULL
     * @param string $email
     * @param string $login
     * @param string $password
     * @param string $hash
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        string $last_name,
        string $first_name,
        string $middle_name,
        ?int $id_user_info_department,
        ?int $id_user_info_position,
        string $email,
        string $login,
        string $password,
        string $hash
    ): int {

        $bindParams = [
            $last_name,
            $first_name,
            $middle_name,
            $id_user_info_department,
            $id_user_info_position,
            $email,
            $login,
            $password,
            $hash
        ];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `user`
                    (`id`, `last_name`, `first_name`, `middle_name`, `id_user_info_department`, `id_user_info_position`, `email`, `login`, `password`, `hash`, `date_reg`, `is_banned`, `num_incorrect_password_input`)
                  VALUES
                    (NULL, {$values}, UNIX_TIMESTAMP(), 0, 0)";
        return ParametrizedQuery::set($query, $bindParams);
    }


    /**
     * Предназначен для получения ассоциативного массива пользователя по его логину
     *
     * @param string $login логин пользователя
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAssocByLogin(string $login): ?array
    {
        $query = "SELECT `user`.`id`,
                         `user`.`first_name`,
                         `user`.`middle_name`,
                         `user`.`last_name`,
                         `user_info_department`.`long_name` AS `department`,
                         `user_info_position`.`name` AS `position`,
                         `user`.`email`,
                         `user`.`login`,
                         `user`.`password`,
                         `user`.`is_banned`,
                         `user`.`num_incorrect_password_input`
				  FROM (SELECT *
				        FROM `user`
				        WHERE `user`.`login`=?) AS `user`
                  LEFT JOIN `user_info_department`
                        ON (`user`.`id_user_info_department`=`user_info_department`.`id`)
                  LEFT JOIN `user_info_position`
                        ON (`user`.`id_user_info_position`=`user_info_position`.`id`)";
        $result = ParametrizedQuery::getFetchAssoc($query, [$login]);
        return $result ? $result[0] : null;
    }


    /**
     * Предназначен для получения ассоциативных массивов ролей пользователя по его id
     *
     * @param int $id id пользователя
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAllRolesAssocByUserId(int $id): ?array
    {
        $query = "SELECT `user_info_role`.`name`,
                         `user_info_role`.`system_value`
                  FROM (SELECT *
				        FROM `user_role`
				        WHERE `user_role`.`id_user`=?) AS `user_role`
                  LEFT JOIN `user_info_role`
                         ON (`user_role`.`id_user_info_role`=`user_info_role`.`id`)";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для инкрементирования счетчика неверно введенных паролей пользователя по его id
     *
     * @param int $id id пользователя
     * @throws DataBaseEx
     */
    static public function incrementIncorrectPasswordInputById(int $id): void
    {
        $query = "UPDATE `user`
                  SET `num_incorrect_password_input`=`num_incorrect_password_input`+1
                  WHERE `id`=?";

        ParametrizedQuery::set($query, [$id]);
    }


    /**
     * Предназначен для обнуления счетчика неверно введенных паролей пользователя по его id
     *
     * @param int $id id пользователя
     * @throws DataBaseEx
     */
    static public function zeroingIncorrectPasswordInputById(int $id): void
    {
        $query = "UPDATE `user`
                  SET `num_incorrect_password_input`=0
                  WHERE `id`=?";

        ParametrizedQuery::set($query, [$id]);
    }


    /**
     * Предназначен для бана пользователя по его id
     *
     * @param int $id id пользователя
     * @throws DataBaseEx
     */
    static public function setBanById(int $id): void
    {
        $query = "UPDATE `user`
                  SET `is_banned`=1
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    /**
     * @todo в разработке
     *
     * @return array
     * @throws DataBaseEx
     */
    static public function getActiveExperts()
    {
        // Получение экспертов (Сотрудник экспертного отдела / todo ???Сотрудник сметного отдела)
        $query = "SELECT `user`.`id`,
	                     `user`.`last_name`,
	                     `user`.`first_name`,
	                     `user`.`middle_name`
                  FROM `user`
                  WHERE EXISTS (SELECT * FROM `user_role`
                                WHERE `user_role`.`id_user`=`user`.`id` AND `user_role`.`id_user_info_role` IN (3, 4, 5))";

        $experts =  SimpleQuery::getFetchAssoc($query);

        return $experts;

        $query = "SELECT `id`,
                         `id_substitutional`,
                         `id_substitutable`
                  FROM `expert_substitution`";
        $substitution = SimpleQuery::getFetchAssoc($query) ?? [];

        foreach ($experts as $exp_ind => $expert) {

            foreach ($substitution as $sub_ind => $sub) {

                // Эксперт замещает другого эксперта
                if ($expert['id'] == $sub['id_substitutional']) {

                    // Замещаемые эксперты
                    $substitutable = array_filter($experts, fn($tmp) => ($tmp['id'] == $sub['id_substitutable']));

                    if(empty($substitutable)){
                        throw new SelfEx("В замещении id: '{$sub['id']}' не найден замещаемый эксперт с id: '{$sub['id_substitutional']}'");
                    }

                    foreach ($substitutable as $tmp => $tmp) {
                        $experts[$tmp]['substitutionals'][] = $expert;
                    }

                    unset($substitution[$sub_ind]);
                }
            }

            if (!isset($experts[$exp_ind]['substitutionals'])) {
                $experts[$exp_ind]['substitutionals'] = null;
            }
        }

        var_dump($experts);

    }
}