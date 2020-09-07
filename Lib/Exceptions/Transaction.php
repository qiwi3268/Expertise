<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе класса Transaction
// code:
//  1 - переданный класс не существует
//  2 - переданный метод не существует
//  3 - Переданное количество параметров меньше минимального, которое принимает метод
//  4 - Переданное количество параметров больше максимального, которое принимает метод
//
class Transaction extends \Exception
{
    use MainTrait;
}
