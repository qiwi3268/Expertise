<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса логирования сообщения {@see \Lib\Singles\Logger}
 *
 * 1 - указанный путь должен указывать на существующий файл, доступный для записи<br>
 * 2 - произошла ошибка при попытке записать логируемое сообщение<br>
 *
 */
class Logger extends \Exception
{
    use MainTrait;
}
