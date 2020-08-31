<?php


namespace Classes\Application\Actions;

use Tables\Actions\application;

// Предназначен для реализации действий над типом документа - Заявление
//
class Actions extends \Lib\Actions\Actions {


    // -----------------------------------------------------------------------------------------
    // Реализация абстрактных методов родительского класса
    // -----------------------------------------------------------------------------------------

    protected function getAssocActiveActions():array {

        return application::getAllActive();
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