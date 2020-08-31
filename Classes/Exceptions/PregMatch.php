<?php


namespace Classes\Exceptions;


// exception, связанный с ошибками при работе функции GetHandlePregMatch
// code:
//  1 - Во время выполнения функции произошла ошибка или нет вхождений шаблона в строку
//
class PregMatch extends \Exception{

    use \Lib\Exceptions\Traits\MainTrait;
}