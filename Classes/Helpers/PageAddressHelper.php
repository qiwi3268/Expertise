<?php

// Предназначен для вспомогательной работы с адресом страницы
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
}