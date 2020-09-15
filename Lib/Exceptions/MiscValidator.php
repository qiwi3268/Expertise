<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе csp класса классов валидации справочников:
 *
 * {@see \Lib\Miscs\Validation\Validator}<br>
 * 1 - передано некорректное значение справочника
 * 2 - класс не сущетвует
 * 3 - класс не реализует интерфейс
 * 4 - запрашиваемое значение справочника не существует
 * {@see \Lib\Miscs\Validation\DependentMisc}<br>
 * 5 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
 *
 */
class MiscValidator extends \Exception
{
    use MainTrait;
}