<?php


namespace Tables\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе классов таблиц
 *
 * {@see \Tables\Helpers\Helper}<br>
 * 1001 - в массиве result отсутствует(ют) свойства id_misc и/или name_misc<br>
 * {@see \Tables\Docs\Relations\ParentDocumentLinker}<br>
 * 2001 - метод невозможно вызвать от типа документа "Заявление"<br>
 * 2002 - методу не удалось определить тип документа<br>
 * {@see \Tables\Locators\DocumentTypeTableLocator}<br>
 * 3001 - получен неопределенный тип документа<br>
 * 3002 - в маппинге метода не найдено совпадения для типа документа<br>
 * {@see \Tables\Locators\TypeOfObjectTableLocator}<br>
 * 4001 - получен неопределенный вид объекта<br>
 * {@see \Tables\FinancingSources\FinancingSourcesAggregator}<br>
 * 5001 - получен неопределенный вид объекта<br>
 *
 *
 */
class Tables extends \Exception
{
    use MainTrait;
}
