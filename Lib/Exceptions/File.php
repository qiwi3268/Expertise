<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


// exception, связанный с ошибками при работе файловых классов:
// Lib\Files\Upload:
//      размерность массива uploadNames не соответствует имеющемуся количеству файлов
// Lib\Files\Mappings\RequiredMappingsSetter
//      запрашиваемый mapping_level_1 не существует в FILE_TABLE_MAPPING
//      запрашиваемый mapping_level_2 не существует в mapping_level_1 в FILE_TABLE_MAPPING
// Lib\Files\Initialization\Initializer
//      ошибка в маппинг таблице (файлов)
//      ошибка в маппинг таблице (подписей)
//      осталась(лись) подпись, которая не подошла ни к одному из файлов
//      в массиве файлов не найден нужный id
class File extends \Exception
{
    use MainTrait;
}

