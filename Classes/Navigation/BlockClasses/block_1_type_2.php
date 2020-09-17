<?php


namespace Classes\Navigation\BlockClasses;

use Classes\Navigation\NavigationTable;


/**
 * Реализация класса XML схемы навигации пользователя
 *
 * block1 - "Мои документы" для Заявителя<br>
 * type2 - "Мои заявления"<br>
 *
 * Параметры запроса:<br>
 * заявление сохранено;<br>
 * пользователь является автором;<br>
 *
 */
class block_1_type_2 extends NavigationTable
{

    /**
     * Реализация абстрактного метода
     *
     * @return string
     */
    static protected function getSection(): string
    {
        $section = "FROM `doc_application`
                    WHERE `is_saved`='1' AND `doc_application`.`id_author`=?";
        return $section;
    }
}
