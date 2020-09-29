<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса {@see \Lib\DefaultFormParameters\DefaultFormParametersCreator}
 *
 * 1 - в dataMapping по ключу ... отсутствует ключ 'class' или 'method'<br>
 * 2 - в результатах транзакции отсутствуют данные из вызова метода<br>
 * 3 - в dataMapping нет элемента по запрашиваемому ключу
 *
 */
class DefaultFormParameters extends \Exception
{
    use MainTrait;
}
