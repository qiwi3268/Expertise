<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса базы данных {@see \Lib\Miscs\Initialization\Initializer}
 *
 * 1 - возникла ошибка при работе функции class_implements<br>
 * 2 - класс справочника не реализует один из требуемых интерфейсов: SingleMisc / DependentMisc<br>
 * 3 - вызван метод Lib\Miscs\Initialization\Initializer::getPaginationSingleMiscs при пустом массиве singleMiscs<br>
 * 4 - вызван метод Lib\Miscs\Initialization\Initializer::getPaginationDependentMiscs при пустом массиве dependentMiscs
 *
 */
class MiscInitializer extends \Exception
{
    use MainTrait;
}
