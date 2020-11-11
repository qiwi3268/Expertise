<?php


namespace Classes\RouteCallbacks\DocumentParameters;

use Lib\Exceptions\URIParser as URIParserEx;
use Lib\Singles\URIParser;


/**
 * Предназначен для объявления констант открытого документа из страницы открытого действия:
 *
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 *
 */
class ActionPage
{

    /**
     * Предназначен для объявления констант
     *
     * @throws URIParserEx
     */
    public function defineDocumentParameters(): void
    {
        $result = URIParser::parseActionPage(URI);

        define('CURRENT_DOCUMENT_TYPE', $result['document_type']);
        define('CURRENT_DOCUMENT_ID', $result['document_id']);
    }
}