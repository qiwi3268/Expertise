<?php


namespace Classes\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе c классами получения параметров открытого документа
 *
 * {@see \Classes\RouteCallbacks\DocumentParameters\DocumentParameters}<br>
 * 2 - произошла ошибка при определении типа открытого документа<br>
 * 3 - открытый тип документа не определен в константе DOCUMENT_TYPE<br>
 * 4 - id открытого документа не является целочисленным значением<br>
 * {@see \Classes\RouteCallbacks\DocumentParameters\ActionPage}<br>
 * 1 - id открытого документа не существует в GET параметрах<br>
 * {@see \Classes\RouteCallbacks\DocumentParameters\ExpertiseCard}<br>
 * 1 - id открытого документа не существует в GET параметрах
 *
 */
class DocumentParameters extends \Exception
{
    use MainTrait;
}