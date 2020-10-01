<?php


namespace functions\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе функций в файле \functions\functions.php
 *
 * 1 - результат вызова функции array_walk_recursive вернул false<br>
 *
 */
class Functions extends \Exception
{
    use MainTrait;
}
