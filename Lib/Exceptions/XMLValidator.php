<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе класса Lib\Singles\XMLValidator
// code:
//  1 - в узле не найден обязательный аттрибут
//  2 - в узле имеются аттрибуты, в то время как их не должно быть
//  3 - в узле не найден обязательный дочерний узел узел
//  4 - в узле имеются дочерние узлы, в то время как их не должно быть
//
class XMLValidator extends \Exception
{
    use MainTrait;
}
