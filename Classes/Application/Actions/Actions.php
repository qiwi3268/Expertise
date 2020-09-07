<?php


namespace Classes\Application\Actions;

use core\Classes\Session;
use Lib\Actions\Actions as MainActions;
use Lib\Singles\VariableTransfer;
use Classes\Application\Responsible as ApplicationResponsible;
use Tables\Actions\application;


// Предназначен для реализации действий над типом документа - Заявление
//
class Actions extends MainActions
{
    private $applicationId;

    public function __construct()
    {
        $this->applicationId = clearHtmlArr($_GET)['id_application'];

        parent::__construct();

        $test = 1;
        $test = 2;

        // todo Ответственных из трансфера, которые м.б. передались через хедер
    }

    // -----------------------------------------------------------------------------------------
    // Реализация абстрактных методов родительского класса
    // -----------------------------------------------------------------------------------------

    protected function getAssocActiveActions(): array
    {
        return application::getAllActive();
    }

    protected function getAssocBusinessProcess(): array
    {
        return application::getAssocBusinessProcessById($this->applicationId);
    }


    // Реализация callback'ов действий из БД ---------------------------------------------------

    // "Передать на рассмотрение в ПТО"
    protected function action_1(): bool
    {
        // Тип учетной записи:
        // - заявитель
        if (!Session::isApplicant()) {
            return false;
        }

        // Ответственные
        // - ответственные группы заявителей: 'Полный доступ'
        $responsible = new ApplicationResponsible($this->applicationId);


        // Стадия

        return true;
    }
}