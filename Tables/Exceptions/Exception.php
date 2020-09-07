<?php


namespace Tables\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе классов таблиц
// Tables\application:
//      в массиве result отсутствует(ют) свойства id_misc и/или name_misc
//
class Exception extends \Exception
{
    use MainTrait;
}
