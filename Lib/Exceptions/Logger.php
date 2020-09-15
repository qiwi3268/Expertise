<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса логирования сообщения {@see \Lib\Singles\Logger}
 *
 * 1 - передан некорректный параметр logsDir<br>
 * 2 - передан некорректный параметр logsName<br>
 * 3 - указанный лог файл не существует в файловой системе сервера<br>
 * 4 - указанный лог файл не доступен для записи<br>
 * 5 - произошла ошибка при попытке записать логируемое сообщение<br>
 *
 */
class Logger extends \Exception
{
    use MainTrait;
}
