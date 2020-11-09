<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе c классами табличных маппингов
 *
 * {@see \Lib\TableMappings\TableMappingsXMLHandler}<br>
 * 1001 - ошибка при инициализации XML схемы табличных маппингов<br>
 * 1002 - класс таблицы файлов не существует<br>
 * 1003 - класс таблицы файлов не реализует интерфейс<br>
 * 1004 - тип документа не определен в константе DOCUMENT_TYPE<br>
 * 1005 - класс таблицы подписей не существует<br>
 * 1006 - класс таблицы подписей не реализует интерфейс<br>
 *
 */
class TableMappings extends \Exception
{
    use MainTrait;
}
