<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса разбора URI {@see \Lib\Singles\URIParser}
 *
 * {@see \Lib\Singles\URIParser::getValidatedResult()}<br>
 * 1001 - URI-адрес является некорректным<br>
 * 1002 - в результатах разбора URI отсутствует элемент 'path'<br>
 * 1003 - в результатах разбора URI отсутствует элемент 'query'<br>
 * 1004 - в результатах разбора URI отсутствует элемент 'id_document'<br>
 * 1005 - элемент 'id_document' не является числом или строкой, содержащей число<br>
 * {@see \Lib\Singles\URIParser::parseExpertiseCard()}<br>
 * 2001 - произошла ошибка при разборе URI карточки экспертизы<br>
 * {@see \Lib\Singles\URIParser::parseActionPage()}<br>
 * 3001 - произошла ошибка при разборе URI страницы действия<br>
 * {@see \Lib\Singles\URIParser::parseAPIActionExecutor()}<br>
 * 4001 - произошла ошибка при разборе URI API выполнений действий<br>
 * {@see \Lib\Singles\URIParser::parse()}<br>
 * 5001 - запрос не определен<br>
 * 5002 - произошла ошибка при разборе URI страницы неизвестного типа<br>
 * {@see \Lib\Singles\URIParser::getValidatedDocumentType()}<br>
 * 6001 - тип документа не определен в константе DOCUMENT_TYPE<br>
 *
 */
class URIParser extends \Exception
{
    use MainTrait;
}