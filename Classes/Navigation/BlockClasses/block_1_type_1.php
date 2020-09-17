<?php


namespace Classes\Navigation\BlockClasses;

use Classes\Navigation\NavigationTable;


/**
 * Реализация класса XML схемы навигации пользователя
 *
 * block1 - "Мои документы" для Заявителя<br>
 * type1 - ""
 *
 * Параметры запроса:<br>
 *
 */
class block_1_type_1 extends NavigationTable
{

    /**
     * @todo заглушка
     * Реализация абстрактного метода
     *
     * @return string
     */
    static protected function getSection(): string
    {
        $section = "FROM `doc_application`
                    WHERE `is_saved`='0' AND `doc_application`.`id_author`=?";
        return $section;
    }
}
