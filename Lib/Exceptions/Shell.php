<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе класса Lib\CSP\Shell
// code:
//  1 - исполняемая команда не произвела вывод или произошла ошибка
//
class Shell extends \Exception
{
    use MainTrait;
}