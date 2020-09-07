<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе Responsible класса Responsible и XMLReader
// code:
//  1 - передан некорректный тип документа
//  2 - методу Lib\Singles\Helpers\PageAddress::getDocumentType не удалось определить тип документа
//  3  - не существует указанного названия группы доступа заявителя к заявлению
class Responsible extends \Exception
{
    use MainTrait;
}