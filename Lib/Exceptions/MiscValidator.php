<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе MiscValidator класса Validator
// code:
//  1 - передано некорректное значение справочника
//  2 - класс не сущетвует
//  3 - класс не реализует интерфейс
//  4 - запрашиваемое значение справочника не существует
//  5 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
//
class MiscValidator extends \Exception
{
    use MainTrait;
}