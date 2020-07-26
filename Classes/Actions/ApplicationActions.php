<?php


// Предназначен для реализации действий над типом документа - Заявление
//
class ApplicationActions extends Actions{


    // -----------------------------------------------------------------------------------------
    // Реализация абстрактных методов родительского класса
    // -----------------------------------------------------------------------------------------

    protected function getAssocActiveActions():array {

        return action_applicationTable::getAllActive();
    }

    protected function getAssocBusinessProcess():array {

        return []; //todo прикручиваем запрос из БД
    }


    // -----------------------------------------------------------------------------------------
    // Реализация callback'ов действий из БД
    // -----------------------------------------------------------------------------------------

    protected function test1():bool {
        return true;
    }

    protected function test2():bool {
        return true;
    }

    protected function test3():bool {
        return true;
    }

    protected function test4():bool {
        return true;
    }
}