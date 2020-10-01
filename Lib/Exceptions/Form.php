<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе c классами html-форм
 * {@see \Lib\Form\DefaultFormParametersCreator}<br>
 * 1001 - в dataMapping по ключу ... отсутствует ключ 'class' или 'method'<br>
 * 1002 - в результатах транзакции отсутствуют данные из вызова метода<br>
 * 1003 - в dataMapping нет элемента по запрашиваемому ключу
 *
 */
class Form extends \Exception
{
    use MainTrait;
}
