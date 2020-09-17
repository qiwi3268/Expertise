<?php


namespace Classes\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе функции {@see getHandlePregMatch}
 *
 * 1 - во время выполнения функции произошла ошибка или нет вхождений шаблона в строку
 *
 */
class PregMatch extends \Exception
{
    use MainTrait;
}