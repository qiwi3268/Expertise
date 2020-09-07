<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе c действиями
// code:
// 	1 - ошибка при определении типа документа
// 	2 - в классе типа документа отсутствует callback-метод из БД
//  3 - свойство callbackName класса ActionsSidebar имеет значение null. При этом
//		произошла попытка проверки доступа к текущему действию
//	4 - callback-метод дочернего класса возвращает значение, не принадлежащее типу boolean
//
class Actions extends \Exception
{
    use MainTrait;
}
