<?php


namespace Lib\Exceptions;


// exception, связанный с ошибками при работе класса Shell
//      исполняемая команда не произвела вывод или произошла ошибка
//
class Shell extends \Exception
{
    use \Lib\Exceptions\Traits\MainTrait;
}