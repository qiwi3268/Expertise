<?php


namespace Lib\Singles\Helpers;

use core\Classes\Session;


// Вспомогательный класс. Предназначен для вспомогательной работы с адресом страницы
//
class PageAddress
{

    // Предназначен для получения типа открытого документа
    // Возвращает параметры----------------------------------
    // string : тип открытого документа
    // null   : в случае, если открытая страница не принадлежит к определенным типам документа
    //
    static public function getDocumentType(): ?string
    {
        if (containsAll(URN, DOCUMENT_TYPE['application'])) {
            return DOCUMENT_TYPE['application'];
        }
        return null;
    }


    // Предназначен для получения дефолтных GET-параметров для навигационной страницы (/home/navigation)
    // Параметры являются персональными для каждой из роли
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив с параметрами b и v
    //
    static public function getDefaultNavigationPage(): array
    {
        $roles = Session::getUserRoles();

        if (in_array(ROLE['APP'], $roles)) return ['b' => 'block_2', 'v' => 'view_2'];
        //elseif...
    }
}