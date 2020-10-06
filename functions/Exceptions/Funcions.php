<?php


namespace functions\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе функций в файле \functions\functions.php
 * {@see getArrayWithReplacedNullValues()}<br>
 * 1 - результат вызова функции array_walk_recursive вернул false<br>
 * {@see getHandlePregMatch()}<br>
 * 2 - во время выполнения функции произошла ошибка или нет вхождений шаблона в строку<br>
 *
 */
class Functions extends \Exception
{
    use MainTrait;
}
