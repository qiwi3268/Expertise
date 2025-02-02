<?php


namespace core\Classes\Exceptions;
use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе c классами обработки запросов на сервер
 *
 * {@see \core\Classes\Request\Request}<br>
 * 1001 - запрашиваемый параметр по ключу не существует<br>
 * {@see \core\Classes\Request\HttpRequest}<br>
 * 2001 - получен неопределенный метод запроса на сервер<br>
 *
 */
class Request extends \Exception
{
    use MainTrait;
}