<?php


namespace Tables\Docs;

use Tables\Exceptions\Tables as SelfEx;


/**
 * Предназначен для получения названий классов документов / отношений документов по типу документа
 *
 */
class TableLocator
{

    /**
     * Предназначен для получения названия класса таблицы документа по принятому типу документа
     *
     * @param string $documentType тип документа
     * @return string название класса таблицы
     * @throws SelfEx
     */
    public function getDocTableByDocumentType(string $documentType): string
    {
        switch ($documentType) {
            case DOCUMENT_TYPE['application'] :
                return '\Tables\Docs\application';
            case DOCUMENT_TYPE['total_cc'] :
                return '\Tables\Docs\total_cc';
            case DOCUMENT_TYPE['section_documentation_1'] :
                return '\Tables\Docs\section_documentation_1';
            case DOCUMENT_TYPE['section_documentation_2'] :
                return '\Tables\Docs\section_documentation_2';
            default :
                throw new SelfEx("Методу Tables\Docs\TableLocator::getDocTableByDocumentType не удалось определить таблицу документа для documentType: '{$documentType}'", 3);
        }
    }


    /**
     * Предназначен для получения названия класса таблицы отношений документа по принятому типу документа
     *
     * @param string $documentType тип документа
     * @return string название класса таблицы
     * @throws SelfEx
     */
    public function getDocRelationTableByDocumentType(string $documentType): string
    {
        switch ($documentType) {
            case DOCUMENT_TYPE['application'] :
                return '\Tables\Docs\Relations\application';
            case DOCUMENT_TYPE['total_cc'] :
                return '\Tables\Docs\Relations\total_cc';
            case DOCUMENT_TYPE['section_documentation_1'] :
                return '\Tables\Docs\Relations\section_documentation_1';
            default :
                throw new SelfEx("Методу Tables\Docs\TableLocator::getDocRelationTableByDocumentType не удалось определить таблицу отношений документа для documentType: '{$documentType}'", 4);
        }
    }
}