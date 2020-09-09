<?php


namespace Lib\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Singles\Helpers\PageAddress as PageAddressHelper;
use Classes\Application\Actions\Actions as ApplicationActions;


// Паттерн: ServiceLocator
// Предназначен для получения экземпляра класса действий
//
class Locator
{

    // Сущность (единственный возможный экземпляр) класса
    private static self $instance;

    private Actions $actions;


    // Выполняется единожды
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  1 - ошибка при определении типа документа
    //
    private function __construct(string $documentType)
    {
        switch ($documentType) {

            case DOCUMENT_TYPE['application']:
                $this->actions = new ApplicationActions();
                break;

            default:
                throw new SelfEx('Методу Lib\Actions\Locator::__construct не удалось распознать тип документа', 1);
        }
    }


    // Предназначен для получения сущности класса
    // Возвращает параметры----------------------------------
    // Locator : сущность класса
    //
    static public function getInstance(string $documentType): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self($documentType);
        }
        return self::$instance;
    }


    // Предназначен для получения класса действий в зависимости от того, какой тип документа открыт
    // Возвращает параметры----------------------------------
    // Actions : экземпляр класса
    //
    public function getActions(): Actions
    {
        return $this->actions;
    }
}