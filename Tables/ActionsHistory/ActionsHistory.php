<?php


namespace Tables\ActionsHistory;

use Tables\Exceptions\Tables as TablesEx;
use Lib\Exceptions\DataBase as DataBaseEx;

use Tables\Locators\DocumentTypeTableLocator;
use Tables\Locators\TypeOfObjectTableLocator;


class ActionsHistory
{

    private string $historyTable;

    private int $documentId;


    /**
     * Конструктор класса
     *
     * @param string $documentType
     * @throws TablesEx
     */
    public function __construct(string $documentType)
    {
        $locator = new DocumentTypeTableLocator($documentType);

        $this->historyTable = $locator->getActionsHistory();
    }



}