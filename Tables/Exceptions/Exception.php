<?php


namespace Tables\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе классов таблиц
 *
 * {@see \Tables\Docs\application}<br>
 *    в массиве result отсутствует(ют) свойства id_misc и/или name_misc
 *
 */
class Exception extends \Exception
{
    use MainTrait;
}
