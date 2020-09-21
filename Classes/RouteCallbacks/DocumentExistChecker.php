<?php


namespace Classes\RouteCallbacks;


use Tables\Exceptions\Tables as TablesEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Exception;
use Tables\Docs\TableLocator;


/**
 * Предназначен для проверки существования документа, на который переходит пользователь
 *
 * Для работы класса должны быть определены константы:
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 *
 */
class DocumentExistChecker
{

    /**
     * Название таблицы документа из пакета \Tables\Docs
     *
     */
    private string $documentTable;


    /**
     * Явный конструктор класса
     *
     * Необходим, так как класс создается до того, как будет вызван метод, объявляющий константы
     *
     * @throws TablesEx
     */
    public function construct(): void
    {
        $tableLocator = new TableLocator();
        $this->documentTable = $tableLocator->getDocTableByDocumentType(CURRENT_DOCUMENT_TYPE);
    }


    /**
     * Предназначен для проверки существования текущего документа
     *
     * @throws DataBaseEx
     * @throws Exception
     */
    public function checkDocumentExist(): void
    {
        if (!call_user_func([$this->documentTable, 'checkExistById'], CURRENT_DOCUMENT_ID)) {
            throw new Exception("Запись id: " . CURRENT_DOCUMENT_ID . " документа типа: '" . CURRENT_DOCUMENT_TYPE . "' не существует в БД");
        }
    }
}