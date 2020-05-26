<?php


// exception, связанный с ошибками при работе классов таблиц БД
//
class QueryException extends Exception{

    use Trait_exception;
}
