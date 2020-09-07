<?php


namespace Classes\Application\Actions;

use core\Classes\Session;
use Lib\Actions\Actions as MainActions;
use Tables\Actions\application;


// Предназначен для реализации действий над типом документа - Заявление
//
class Actions extends MainActions
{

    private const DOCUMENT_TYPE = DOCUMENT_TYPE['application'];

    private $applicationId;


    public function __construct()
    {
        $this->applicationId = clearHtmlArr($_GET)['id_application'];

        parent::__construct(self::DOCUMENT_TYPE);
    }

    // -----------------------------------------------------------------------------------------
    // Реализация абстрактных методов родительского класса
    // -----------------------------------------------------------------------------------------
    protected function getDocumentId(): int
    {
        return $this->applicationId;
    }

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

        // Ответственный
        if (!$this->responsible->isUserResponsible(Session::getUserId())) {
            return false;
        }

        // Стадия
        // todo

        return true;
    }

    // "Назначить экспертов"
    protected function action_2(): bool
    {
        // Тип учетной записи:
        // - ПТО
        // todo

        // Ответственный
        // ? (ПТО назначают когда хотят)

        // Стадия
        // ? (ПТО назначают на любой стадии)

        // Нет сводного замечания/заключения, у которого есть назначенные эксперты

        return true;
    }
}