<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе классов валидации справочников:
// code:
// Lib\Miscs\Validation\Validator:
//  1 - передано некорректное значение справочника
//  2 - класс не сущетвует
//  3 - класс не реализует интерфейс
//  4 - запрашиваемое значение справочника не существует
// Lib\Miscs\Validation\DependentMisc
//  5 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
//
class MiscValidator extends \Exception
{
    use MainTrait;
}