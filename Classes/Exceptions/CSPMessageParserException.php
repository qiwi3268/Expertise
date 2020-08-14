<?php


// exception, связанный с ошибками при работе csp класса MessageParser
// code:
//  1 - В БД не нашлось имени из ФИО
//  2 - В одном Signer нашлось больше одного ФИО
class CSPMessageParserException extends Exception{
    
    use Trait_exception;
}