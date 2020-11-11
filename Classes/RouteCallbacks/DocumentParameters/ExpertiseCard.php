<?php


namespace Classes\RouteCallbacks\DocumentParameters;

use Lib\Exceptions\URIParser as URIParserEx;
use Lib\Singles\URIParser;


/**
 * Предназначен для объявления констант открытого документа из карточек экспертизы:
 *
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 * - CURRENT_VIEW_MODE
 *
 */
class ExpertiseCard
{

    /**
     * Предназначен для объявления констант
     *
     * @throws URIParserEx
     */
    public function defineDocumentParameters(): void
    {
        $result = URIParser::parseExpertiseCard(URI);

        define('CURRENT_DOCUMENT_TYPE', $result['document_type']);
        define('CURRENT_DOCUMENT_ID', $result['document_id']);
        define('CURRENT_VIEW_MODE', $result['view_mode']);
    }
}