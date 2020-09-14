<?php


namespace Lib\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Classes\Application\Actions\Actions as ApplicationActions;


/**
 * Предназначен для получения экземпляра класса действий в зависимости от нужного типа документа
 *
 * Паттерн: ServiceLocator
 *
 */
class Locator
{
    /**
     * Сущность класса
     *
     */
    private static self $instance;

    private Actions $actions;


    /**
     * Констркутор класса
     *
     * Вызывается единожды
     *
     * @param string $documentType тип документа
     * @throws SelfEx
     */
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


    /**
     * Предназначен для получения сущности класса
     *
     * @param string $documentType
     * @return static сущность класса
     * @throws SelfEx
     */
    static public function getInstance(string $documentType): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self($documentType);
        }
        return self::$instance;
    }


    /**
     * Предназначен для получения класса действий
     *
     * @return Actions экземпляр класса действий
     */
    public function getActions(): Actions
    {
        return $this->actions;
    }
}