<?php


namespace Classes\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе функции getHandlePregMatch
// code:
//  1 - Во время выполнения функции произошла ошибка или нет вхождений шаблона в строку
//
class PregMatch extends \Exception
{
    use MainTrait;
}