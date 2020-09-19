<?php


namespace Classes\Exceptions;
use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе классов обработки справочников заявления
 *
 * <b>***</b> code соответствуют API_save_form result<br>
 *
 * {@see \Classes\Application\Miscs\Validation\SingleMisc}<br>
 * 4 - передано некорректное значение справочника<br>
 * 5 - запрашиваемое значение справочника не существует<br>
 * {@see \Classes\Application\Miscs\Validation\DependentMisc}<br>
 * 4 - передано некорректное значение справочника<br>
 * 5 - запрашиваемое значение справочника не существует<br>
 * 7 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
 *
 */
class ApplicationFormMiscValidator extends \Exception
{
    use MainTrait;
}