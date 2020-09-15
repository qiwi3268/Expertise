<?php


namespace Classes\Navigation\BlockClasses;

use Classes\Navigation\NavigationTable;


// block1  - "Мои документы" для Заявителя
// type1   - ""

class block_1_type_1 extends NavigationTable
{

    //заглушка
    static protected function getSection(): string
    {
        $section = "FROM `doc_application`
                    WHERE `is_saved`='0' AND `doc_application`.`id_author`=?";
        return $section;
    }
}
