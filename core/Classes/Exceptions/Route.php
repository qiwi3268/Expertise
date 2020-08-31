<?php


namespace core\Classes\Exceptions;


// exception, связанный с ошибками при работе корневого класса Route
//
class Route extends \Exception
{

    use \Lib\Exceptions\Traits\MainTrait;
}