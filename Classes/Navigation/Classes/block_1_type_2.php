<?php


namespace Classes\Navigation\Classes;

use Classes\Navigation\NavigationTable;


// block1  - "Мои документы" - для Заявителя
// type2   - "Мои заявления" - сохранено,
//                             автор - пользователь //todo в зависимости от ролей в заявлении дальше добавлять
class block_1_type_2 extends NavigationTable
{

    static protected function getSection(): string
    {

        $section = "FROM `application`
                    WHERE `is_saved`='0' AND `application`.`id_author`=?";
        return $section;
    }
}
