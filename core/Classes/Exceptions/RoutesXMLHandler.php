<?php


namespace core\Classes\Exceptions;
use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе core класса {@see \core\Classes\RoutesXMLHandler}
 *
 * 1  - ошибка при инициализации XML-схемы маршрутизации<br>
 * 2  - путь к файлу должен начинться и заканчиваться на '/'<br>
 * 3  - файл по пути не существует в файловой системе сервера<br>
 * 4  - пространство имен должно начинться с '\\' и не должено заканчиваться на '\\'<br>
 * 5  - callback класс не существует<br>
 * 6  - тип класса должен быть 'instance' или 'static'<br>
 * 7  - callback метод не существует"
 *
 */
class RoutesXMLHandler extends \Exception
{
    use MainTrait;
}