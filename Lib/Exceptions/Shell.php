<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса {@see \Lib\CSP\Shell}
 *
 * 1 - исполняемая команда не произвела вывод или произошла ошибка
 *
 */
class Shell extends \Exception
{
    use MainTrait;
}