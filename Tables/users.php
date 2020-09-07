<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;
use Lib\DataBase\SimpleQuery;
use Tables\Exceptions\Exception as SelfEx;
use Tables\Helpers\Helper as TableHelper;


final class users
{


    // Предназначен для создания записи пользователя
    // Принимает параметры-----------------------------------
    // Все параметры, согласно таблице `users`
    // За исключением:
    // id_sys_department int/NULL : для заявителя NULL
    // id_sys_position   int/NULL : для заявителя NULL
    // Возвращает параметры-----------------------------------
    // id int : id созданной записи
    //
    static public function create(
        string $last_name,
        string $first_name,
        string $middle_name,
        ?int $id_sys_department,
        ?int $id_sys_position,
        string $email,
        string $login,
        string $password,
        string $hash
    ): int {

        $bindParams = [
            $last_name,
            $first_name,
            $middle_name,
            $id_sys_department,
            $id_sys_position,
            $email,
            $login,
            $password,
            $hash
        ];
        $values =TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `users`
                    (`id`, `last_name`, `first_name`, `middle_name`, `id_sys_department`, `id_sys_position`, `email`, `login`, `password`, `hash`, `date_reg`, `is_banned`, `num_incorrect_password_input`)
                  VALUES
                    (NULL, {$values}, UNIX_TIMESTAMP(), 0, 0)";
        return ParametrizedQuery::set($query, $bindParams);
    }


    // Предназначен для получения ассициативного массива пользователя по логину
    // Принимает параметры-----------------------------------
    // login string : логин пользователя
    // Возвращает параметры-----------------------------------
    // array : в случае, если пользователь существует
    // null  : в противном случае
    //
    static public function getAssocByLogin(string $login): ?array
    {
        $query = "SELECT `users`.`id`,
                         `users`.`first_name`,
                         `users`.`middle_name`,
                         `users`.`last_name`,
                         `sys_department`.`long_name` AS `department`,
                         `sys_position`.`name` AS `position`,
                         `users`.`email`,
                         `users`.`login`,
                         `users`.`password`,
                         `users`.`is_banned`,
                         `users`.`num_incorrect_password_input`
				  FROM (SELECT *
				        FROM `users`
				        WHERE `users`.`login`=?) AS `users`
                  LEFT JOIN `sys_department`
                        ON (`users`.`id_sys_department`=`sys_department`.`id`)
                  LEFT JOIN `sys_position`
                        ON (`users`.`id_sys_position`=`sys_position`.`id`)";
        $result = ParametrizedQuery::getFetchAssoc($query, [$login]);
        return $result ? $result[0] : null;
    }


    // Предназначен для получения ассициативного массива ролей пользователя по его id
    // Принимает параметры-----------------------------------
    // id int : id пользователя
    // Возвращает параметры-----------------------------------
    // array : в случае, если роли пользователя существуют
    // null  : в противном случае
    //
    //todo среднее пересмотреть запрос
    static public function getRolesById(int $id): ?array
    {
        $query = "SELECT `sys_role`.`name`,
                         `sys_role`.`system_value`
                  FROM (SELECT *
				        FROM `users_role`
				        WHERE `users_role`.`id_user`=?) AS `users_role`
                  LEFT JOIN `sys_role`
                         ON (`users_role`.`id_sys_role`=`sys_role`.`id`)";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result : null;
    }


    // Предназначен для инкрементирования счетчика неверно введенных паролей пользователя по его id
    // Принимает параметры-----------------------------------
    // id int : id пользователя
    //
    static public function incrementIncorrectPasswordInputById(int $id): void
    {
        $query = "UPDATE `users`
                  SET `num_incorrect_password_input`=`num_incorrect_password_input`+1
                  WHERE `id`=?";

        ParametrizedQuery::set($query, [$id]);
    }


    // Предназначен для обнуления счетчика неверно введенных паролей пользователя по его id
    // Принимает параметры-----------------------------------
    // id int : id пользователя
    //
    static public function zeroingIncorrectPasswordInputById(int $id): void
    {
        $query = "UPDATE `users`
                  SET `num_incorrect_password_input`=0
                  WHERE `id`=?";

        ParametrizedQuery::set($query, [$id]);
    }


    // Предназначен для бана пользователя по его id
    // Принимает параметры-----------------------------------
    // id int : id пользователя
    //
    static public function banById(int $id): void
    {
        $query = "UPDATE `users`
                  SET `is_banned`=1
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }




    static public function getActiveExperts()
    {
        // Получение экспертов (Сотрудник экспертного отдела / todo ???Сотрудник сметного отдела)
        $query = "SELECT DISTINCT `users`.`id`,
	                              `users`.`last_name`,
	                              `users`.`first_name`,
	                              `users`.`middle_name`
                 FROM `users`
                 INNER JOIN `users_role`
	                ON (`users_role`.`id_user`=`users`.`id` AND `users_role`.`id_sys_role` IN (3,4))";

        $query = "SELECT `users`.`id`,
	                     `users`.`last_name`,
	                     `users`.`first_name`,
	                     `users`.`middle_name`
                 FROM `users`
                 WHERE EXISTS (SELECT * FROM `users_role`
                               WHERE `users_role`.`id_user`=`users`.`id` AND `users_role`.`id_sys_role` IN (3, 4))";

        $experts =  SimpleQuery::getFetchAssoc($query);

        return $experts;

        $query = "SELECT `id`,
                         `id_substitutional`,
                         `id_substitutable`
                  FROM `expert_substitution`";
        $substitution = SimpleQuery::getFetchAssoc($query) ?? [];

        foreach ($experts as $exp_ind => $expert) {

            foreach ($substitution as $sub_ind => $sub) {

                // Эксперт замещается другим экспертом
                if ($expert['id'] == $sub['id_substitutable']) {

                    // Замещающие эксперты
                    $substitutionals = array_filter($experts, fn($tmp) => ($tmp['id'] == $sub['id_substitutional']));

                    if(empty($substitutionals)){
                        throw new SelfEx("В замещении id: '{$sub['id']}' не найден замещающий эксперт с id: '{$sub['id_substitutional']}'");
                    }

                    foreach ($substitutionals as $substitutional) {
                        $experts[$exp_ind]['substitutionals'][] = $substitutional;
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