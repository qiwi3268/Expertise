<?php


// Паттерн: ServiceLocator
// Предназначен для получения экземпляра класса действий
//
class LocatorActions{

    // Сущность (единственный возможный экземпляр) класса
    private static LocatorActions $instance;

    private Actions $Actions;


    // Выполняется единожды
    private function __construct(){

        $documentType = PageAddressHelper::getDocumentType();

        if(is_null($documentType)){

            throw new ActionsException('Методу getDocumentType класса PageAddressHelper не удалось определить тип документа', 1);
        }

        switch($documentType){

            case _DOCUMENT_TYPE['application']:
                $this->Actions = new ApplicationActions();
                break;

            default:
                throw new ActionsException('Методу __construct класса LocatorActions не удалось распознать тип документа', 1);
        }
    }


    // Предназначен для получения сущности класса
    // Возвращает параметры----------------------------------
    // LocatorActions : сущность класса
    //
    static public function getInstance():LocatorActions {

        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }


    // Предназначен для получения класса действий в зависимости от того, какой тип документа открыт
    // Возвращает параметры----------------------------------
    // Actions : сущность класса
    //
    public function getActions():Actions {

        return $this->Actions;
    }
}