<?php


namespace Lib\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Tables\Exceptions\Tables as TablesEx;

use Classes\Application\Actions\Actions as ApplicationActions;
use Classes\TotalCC\Actions\Actions as TotalCCActions;
use Classes\Section\Actions\Actions as SectionActions;


/**
 * Предназначен для получения экземпляра класса действий в зависимости от нужного типа документа
 *
 * Паттерн: <i>ServiceLocator</i>
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
     * @throws DataBaseEx
     * @throws TablesEx
     * @throws DocumentTreeHandlerEx;
     */
    private function __construct(string $documentType)
    {
        switch ($documentType) {

            case DOCUMENT_TYPE['application'] :
                $this->actions = new ApplicationActions();
                break;

            case DOCUMENT_TYPE['total_cc'] :
                $this->actions = new TotalCCActions();
                break;

            case DOCUMENT_TYPE['section_documentation_1'] :
            case DOCUMENT_TYPE['section_documentation_2'] :
                $this->actions = new SectionActions();
                break;

            default :
                throw new SelfEx('Методу Lib\Actions\Locator::__construct не удалось распознать тип документа', 1);
        }
    }


    /**
     * Предназначен для получения сущности класса
     *
     * @param string $documentType тип документа
     * @return static сущность класса
     * @throws DataBaseEx
     * @throws SelfEx
     * @throws TablesEx
     * @throws DocumentTreeHandlerEx
     */
    static public function getInstance(string $documentType): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self($documentType);
        }
        return self::$instance;
    }


    /**
     * Предназначен для получения экземпляра класса
     *
     * @return Actions экземпляр класса действий
     */
    public function getObject(): Actions
    {
        return $this->actions;
    }
}