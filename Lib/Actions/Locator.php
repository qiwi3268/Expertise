<?php


namespace Lib\Actions;

use Lib\Exceptions\Actions as SelfException;


// Паттерн: ServiceLocator
// Предназначен для получения экземпляра класса действий
//
class Locator
{

    // Сущность (единственный возможный экземпляр) класса
    private static self $instance;

    private Actions $Actions;


    // Выполняется единожды
    private function __construct()
    {

        $documentType = \Lib\Singles\Helpers\PageAddress::getDocumentType();

        if (is_null($documentType)) {

            throw new SelfException('Методу PageAddress::getDocumentType не удалось определить тип документа', 1);
        }

        switch ($documentType) {

            case DOCUMENT_TYPE['application']:
                $this->Actions = new \Classes\Application\Actions\Actions();
                break;

            default:
                throw new SelfException('Методу Locator::__construct не удалось распознать тип документа', 1);
        }
    }


    // Предназначен для получения сущности класса
    // Возвращает параметры----------------------------------
    // Locator : сущность класса
    //
    static public function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    // Предназначен для получения класса действий в зависимости от того, какой тип документа открыт
    // Возвращает параметры----------------------------------
    // Actions : сущность класса
    //
    public function getActions(): Actions
    {
        return $this->Actions;
    }
}