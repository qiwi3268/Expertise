<?php


// exception, связанный с ошибками при работе функции GetHandlePregMatch
// code:
//  1 - Во время выполнения функции произошла ошибка или нет вхождений шаблона в строку
//
class PregMatchException extends Exception{
    
    use Trait_exception;
}