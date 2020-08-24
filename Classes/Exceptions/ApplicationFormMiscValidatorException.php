<?php


// exception, связанный с ошибками при работе классов обработки справочников заявления SingleMiscValidator и
// DependentMiscValidator
// *** code соответствуют API_save_form result
// code:
//  4 - передано некорректное значение справочника
//  5 - запрашиваемое значение справочника не существует
class ApplicationFormMiscValidatorException extends Exception{
    
    use Trait_exception;
}