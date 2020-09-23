<?php


namespace Tables\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе классов таблиц
 *
 * {@see \Tables\Docs\application}<br>
 * 1 - в массиве result отсутствует(ют) свойства id_misc и/или name_misc<br>
 * {@see \Tables\Docs\Relations\HierarchyTree}<br>
 * 2 - не удалось определить тип документа<br>
 * {@see \Tables\Docs\TableLocator}<br>
 * 3 - методу Tables\Docs\TableLocator::getDocTableByDocumentType не удалось определить таблицу документа<br>
 * 4 - методу Tables\Docs\TableLocator::getDocRelationTableByDocumentType не удалось определить таблицу отношений документа<br>
 * {@see \Tables\DocumentationTypeTableLocator}<br>
 * 5 - получен неопределенный вид объекта<br>
 *
 *
 *
 */
class Tables extends \Exception
{
    use MainTrait;
}
