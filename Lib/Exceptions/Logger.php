<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе класса Logger
// code:
//  1 - передан некорректный параметр logsDir
//  2 - передан некорректный параметр logsName
//  3 - указанный лог файл не существует в файловой системе сервера
//  4 - указанный лог файл не доступен для записи
//  5 - произошла ошибка при попытке записать логируемое сообщение
//
class Logger extends \Exception
{
    use MainTrait;
}
