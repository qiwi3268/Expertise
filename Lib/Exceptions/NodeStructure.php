<?php


namespace Lib\Exceptions;


// exception, связанный с ошибками при работе MiscValidator класса Validator
// code:
//  1 - у узла отсутствует родительский узел
//
class NodeStructure extends \Exception
{
    use \Lib\Exceptions\Traits\MainTrait;
}