<?php


namespace Lib\Exceptions;


// exception, связанный с ошибками при работе c действиями
// code:
// 	1 - Ошибка при определении типа документа
// 	2 - В классе типа документа отсутствует callback-метод из БД
//  3 - Свойство callbackName класса ActionsSidebar имеет значение null.
//		При этом произошла попытка проверки доступа к текущему действию
//	4 - callback-метод дочернего класса возвращает значение, не принадлежащее типу boolean
//
class Actions extends \Exception
{
    use \Lib\Exceptions\Traits\MainTrait;
}
