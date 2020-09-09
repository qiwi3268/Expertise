<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе c действиями
// code:
// Lib\Actions\Locator:
// 	1 - ошибка при определении типа документа
// Lib\Actions\AccessAction:
//  2 - попытка получить доступ к несуществующему действию для страницы
//  3 - отсутствует метод доступа к действию
//  4 - метод доступа действию возвращает значение, не принадлежащее типу boolean
// Lib\Actions\ExecutionAction:
//  5 - отсутствует метод выполнения действия
//  6 - не найден метод выполнения действия по требуемому hash'у
//
class Actions extends \Exception
{
    use MainTrait;
}
