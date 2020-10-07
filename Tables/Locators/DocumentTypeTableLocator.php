<?php


namespace Tables\Locators;

use Tables\Exceptions\Tables as SelfEx;


/**
 * Предназначен для получения названий классов таблиц в зависимости от типа документа
 *
 */
class DocumentTypeTableLocator
{

    /**
     * Тип документа
     *
     */
    private string $documentType;


    /**
     * Конструктор класса
     *
     * @param string $documentType
     * @throws SelfEx
     */
    public function __construct(string $documentType)
    {
        if (!isset(DOCUMENT_TYPE[$documentType])) {
            throw new SelfEx("Получен неопределенный тип документа: '{$documentType}'", 3001);
        }
        $this->documentType = $documentType;
    }


    /**
     * Предназначен для обработки маппинга вызывающего метода
     *
     * @param array $mapping
     * @return string
     * @throws SelfEx
     */
    private function handleMapping(array $mapping): string
    {
        if (!isset($mapping[$this->documentType])) {

            $class = __CLASS__;
            $callingMethodName = getCallingFunctionName();

            throw new SelfEx("В маппинге метода: '{$class}::{$callingMethodName}' не найдено совпадения для типа документа: '{$this->documentType}'",3002);
        }
        return $mapping[$this->documentType];
    }


    /**
     * Возвращает название класса таблицы из пакета \Tables\Docs
     *
     * @return string
     * @throws SelfEx
     */
    public function getDocs(): string
    {
        $mapping = [
            DOCUMENT_TYPE['application']             => '\Tables\Docs\application',
            DOCUMENT_TYPE['total_cc']                => '\Tables\Docs\total_cc',
            DOCUMENT_TYPE['section_documentation_1'] => '\Tables\Docs\section_documentation_1',
            DOCUMENT_TYPE['section_documentation_2'] => '\Tables\Docs\section_documentation_2'
        ];
        return $this->handleMapping($mapping);
    }


    /**
     * Возвращает название класса таблицы из пакета \Tables\Docs\Relations
     *
     * @return string
     * @throws SelfEx
     */
    public function getDocsRelations(): string
    {
        $mapping = [
            DOCUMENT_TYPE['application']             => '\Tables\Docs\Relations\application',
            DOCUMENT_TYPE['total_cc']                => '\Tables\Docs\Relations\total_cc',
            DOCUMENT_TYPE['section_documentation_1'] => '\Tables\Docs\Relations\section_documentation_1'
        ];
        return $this->handleMapping($mapping);
    }


    /**
     * Возвращает название класса таблицы из пакета \Tables\ActionsHistory
     *
     * @return string
     * @throws SelfEx
     */
    public function getActionsHistory(): string
    {
        $mapping = [
            DOCUMENT_TYPE['application'] => '\Tables\ActionsHistory\application'
        ];
        return $this->handleMapping($mapping);
    }
}