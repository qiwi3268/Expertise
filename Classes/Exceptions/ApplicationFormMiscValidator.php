<?php


namespace Classes\Exceptions;
use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе классов обработки справочников заявления
// Classes\Application\Miscs\Validation\SingleMiscValidator и DependentMiscValidator
// *** code соответствуют API_save_form result
// code:
//  4 - передано некорректное значение справочника
//  5 - запрашиваемое значение справочника не существует
//  7 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
//
class ApplicationFormMiscValidator extends \Exception
{
    use MainTrait;
}