<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе csp класса классов валидации справочников:
 *
 * {@see \Lib\Miscs\Validation\Validator}<br>
 * 1 - передано некорректное значение справочника<br>
 * 2 - класс не сущетвует<br>
 * 3 - класс не реализует интерфейс<br>
 * 4 - запрашиваемое значение справочника не существует<br>
 * {@see \Lib\Miscs\Validation\DependentMisc}<br>
 * 5 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
 *
 */
class MiscValidator extends \Exception
{
    use MainTrait;
}