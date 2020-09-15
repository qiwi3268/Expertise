<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса {@see \Lib\Singles\NodeStructure}
 *
 * 1 - у узла отсутствует родительский узел
 *
 */
class NodeStructure extends \Exception
{
    use MainTrait;
}