<?php


// Вспомогательный класс. Предназначен для вспомогательной работы с адресом страницы
//
class PageAddressHelper{


    // Предназначен для получения типа открытого документа
    // Возвращает параметры----------------------------------
    // string : тип открытого документа
    // null   : в случае, если открытая страница не принадлежит к определенным типам документа
    //
    static public function getDocumentType():?string {

        if(mb_strpos(_URN_, _DOCUMENT_TYPE['application']) !== false){
            return _DOCUMENT_TYPE['application'];
        }

        return null;
    }
    
    
    // Предназначен для получения дефолтных GET-параметров для навигационной страницы (/home/navigation)
    // Параметры являются персональными для каждой из роли
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив с параметрами b и v
    //
    static public function getDefaultNavigationPage():array {
        
        $roles = Session::getUserRoles();
        
        if(in_array(_ROLE['APP'], $roles)) return ['b' => 'block_2', 'v' => 'view_2'];
        //elseif...
    }
    
    
}