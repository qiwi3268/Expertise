<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса базы данных {@see \Lib\Singles\TemplateMaker}
 *
 * 1 - шаблон уже существует<br>
 * 2 - указанный абсолютный футь в ФС сервера к шаблону уже существует в другом шаблоне<br>
 * 3 - файл шаблона по пути ... не существует в ФС сервера<br>
 * 4 - запрашиваемый шаблон не существует<br>
 * 5 - по пути ... не найден шаблон для получения данных
 *
 */
class TemplateMaker extends \Exception
{
    use MainTrait;
}
