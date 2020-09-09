<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе класса Lib\DataBase\DataBase
// code:
//  1 - переданный параметр не подходит под указанные типы
//      ошибка подключения к базе данных
//      ошибка в формировании параметризованного запроса
//      ошибка при привязке привязке переменных
//      ошибка при выполнении параметризованного запроса
//      ошибка при получении результата параметризованного запроса
//      ошибка при закрытии параметризованного запроса
//      ошибка при выполнении простого запроса
//      ошибка при старте транзакции
//      ошибка при откате текущей транзакции
//      ошибка при фиксации текущей транзакции
//
class DataBase extends \Exception
{
    use MainTrait;
}
