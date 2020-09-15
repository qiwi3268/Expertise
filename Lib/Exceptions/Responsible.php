<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе с классами действий
 *
 * {@see \Lib\Responsible\Responsible}<br>
 * 1  - передан некорректный тип документа<br>
 * 2  - не существует указанного названия группы доступа заявителя к заявлению<br>
 * {@see \Lib\Responsible\XMLReader}<br>
 * 3  - ошибка при инициализации XML-схемы ответственных<br>
 * 4  - ошибка при получении XML-пути в схеме ответственных<br>
 * 5  - класс в XML-схеме ответственных не существует<br>
 * 6  - метод в XML-схеме ответственных не существует
 *
 */
class Responsible extends \Exception
{
    use MainTrait;
}