<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе класса Lib\Singles\NodeStructure
// code:
//  1 - у узла отсутствует родительский узел
//
class NodeStructure extends \Exception
{
    use MainTrait;
}