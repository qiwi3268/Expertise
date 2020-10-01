<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса базы данных {@see \Lib\DataBase\DataBase}
 *
 * 1 - переданный параметр не подходит под указанные типы<br>
 *
 */
class TemplateMaker extends \Exception
{
    use MainTrait;
}
