<?php


// exception, связанный с ошибками при работе csp класса MessageParser
// code:
//  1 - Во время выполнения функции произошла ошибка или нет вхождений шаблона в строку
//  2 - В БД не нашлось имени из ФИО
//  3 - В одном Signer нашлось больше одного ФИО
class CSPMessageParserException extends Exception{
    
    use Trait_exception;
}