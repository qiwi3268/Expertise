<?php


namespace Tables\Exceptions;


// exception, связанный с ошибками при работе классов таблиц БД
// Tables\applications:
//      в массиве result отсутствует(ют) свойства id_misc и/или name_misc
//
class Exception extends \Exception
{
    use \Lib\Exceptions\Traits\MainTrait;
}
