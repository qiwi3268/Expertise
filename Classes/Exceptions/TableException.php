<?php


// exception, связанный с ошибками при работе классов таблиц БД
//
class TableException extends Exception{
    
    use Trait_exception;
}
