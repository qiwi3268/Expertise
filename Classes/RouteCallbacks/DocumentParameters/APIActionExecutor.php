<?php


namespace Classes\RouteCallbacks\DocumentParameters;

use Lib\Exceptions\URIParser as URIParserEx;
use Lib\Singles\URIParser;


/**
 * Предназначен для объявления констант открытого документа из API выполнений действий:
 *
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 * - CURRENT_PAGE_NAME
 *
 */
class APIActionExecutor
{

    private string $URI;


    /**
     * Конструктор класса
     *
     * @param string $URI
     */
    public function __construct(string $URI)
    {
        $this->URI = $URI;
    }


    /**
     * Предназначен для объявления констант
     *
     * @throws URIParserEx
     */
    public function defineDocumentParameters(): void
    {
        $result = URIParser::parseAPIActionExecutor($this->URI);

        define('CURRENT_DOCUMENT_TYPE', $result['document_type']);
        define('CURRENT_DOCUMENT_ID', $result['document_id']);
        define('CURRENT_PAGE_NAME', $result['page_name']);
    }
}