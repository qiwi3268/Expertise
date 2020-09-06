<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;
use Lib\DataBase\SimpleQuery;
use Tables\Exceptions\Exception as SelfEx;
use Tables\Helpers\Helper as TableHelper;


final class user
{


    // Предназначен для создания записи пользователя
    // Принимает параметры-----------------------------------
    // Все параметры, согласно таблице `user`
    // За исключением:
    // id_user_info_department int/NULL : для заявителя NULL
    // id_user_info_position   int/NULL : для заявителя NULL
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

        $query = "INSERT INTO `user`
                    (`id`, `last_name`, `first_name`, `middle_name`, `id_user_info_department`, `id_user_info_position`, `email`, `login`, `password`, `hash`, `date_reg`, `is_banned`, `num_incorrect_password_input`)
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


    // Предназначен для инкрементирования счетчика неверно введенных паролей пользователя по его id
    // Принимает параметры-----------------------------------
    // id int : id пользователя
    //
    static public function incrementIncorrectPasswordInputById(int $id): void
    {
        $query = "UPDATE `user`
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
        $query = "UPDATE `user`
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
        $query = "UPDATE `user`
                  SET `is_banned`=1
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }




    static public function getActiveExperts()
    {
        // Получение экспертов (Сотрудник экспертного отдела / todo ???Сотрудник сметного отдела)
        $query = "SELECT `user`.`id`,
	                     `user`.`last_name`,
	                     `user`.`first_name`,
	                     `user`.`middle_name`
                  FROM `user`
                  WHERE EXISTS (SELECT * FROM `user_role`
                                WHERE `user_role`.`id_user`=`user`.`id` AND `user_role`.`id_user_info_role` IN (3, 4))";

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