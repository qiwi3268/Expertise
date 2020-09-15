<?php


namespace Lib\Singles\Helpers;

use core\Classes\Session;


/**
 * Вспомогательный класс для работы с адресом страницы
 *
 */
class PageAddress
{

    /**
     * Предназначен для получения дефолтных GET-параметров для навигационной страницы (/home/navigation)
     *
     * Параметры являются персональными для каждой из роли
     *
     * @return array ассоциативный массив с параметрами 'b' и 'v'
     */
    static public function getDefaultNavigationPage(): array
    {
        $roles = Session::getUserRoles();

        if (in_array(ROLE['APP'], $roles)) return ['b' => 'block_2', 'v' => 'view_2'];
        //elseif...
    }
}