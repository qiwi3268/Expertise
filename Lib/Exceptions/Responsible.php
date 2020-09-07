<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе Responsible класса Responsible и XMLReader
// code:
// Lib\Responsible\Responsible:
//  1 - передан некорректный тип документа
//  2 - методу Lib\Singles\Helpers\PageAddress::getDocumentType не удалось определить тип документа
//  3 - не существует указанного названия группы доступа заявителя к заявлению
// Lib\Responsible\XMLReader:
//  4 - ошибка при инициализации XML-схемы ответственных
//  5  - ошибка при получении XML-пути в схеме ответственных
//  6  - класс в XML-схеме ответственных не существует
//  7  - метод в XML-схеме ответственных не существует
//
class Responsible extends \Exception
{
    use MainTrait;
}