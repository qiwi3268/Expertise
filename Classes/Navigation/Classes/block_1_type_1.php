<?php


namespace Classes\Navigation\Classes;

use Classes\Navigation\NavigationTable;


// block1  - "Мои документы" для Заявителя
// type1   - ""

class block_1_type_1 extends NavigationTable
{

    //заглушка
    static protected function getSection(): string
    {

        $section = "FROM `applications`
                    WHERE `is_saved`='0' AND `applications`.`id_author`=?";
        return $section;
    }
}
