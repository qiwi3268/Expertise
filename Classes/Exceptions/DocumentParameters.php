<?php


namespace Classes\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе класса получения параметров открытого документа
// Classes\DocumentParameters\DocumentParameters
// code:
// Classes\DocumentParameters\ActionPage:
//    1  - id открытого документа не существует в GET параметрах
// Classes\DocumentParameters\ActionExecutor:
//    1  - id открытого документа и/или path_name не существует в POST параметрах
// Classes\DocumentParameters\ExpertiseCard:
//    1  - id открытого документа не существует в GET параметрах
//Classes\DocumentParameters\DocumentParameters
//    2  - произошла ошибка при определении типа открытого документа
//    3  - открытый тип документа не определен в константе DOCUMENT_TYPE
//    4  - id открытого документа не является целочисленным значением
//
class DocumentParameters extends \Exception
{
    use MainTrait;
}