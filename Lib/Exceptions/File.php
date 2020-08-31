<?php


namespace Lib\Exceptions;


// exception, связанный с ошибками при работе файловых классов
// Lib\Files\Upload:
//      размерность массива uploadNames не соответствует имеющемуся количеству файлов
// Lib\Files\Mappings\RequiredMappingsSetter
//      запрашиваемый mapping_level_1 не существует в FILE_TABLE_MAPPING
//      запрашиваемый mapping_level_2 не существует в mapping_level_1 в FILE_TABLE_MAPPING
// Lib\Files\Initialization\Initializator
//      ошибка в маппинг таблице (файлов)
//      ошибка в маппинг таблице (подписей)
//      осталась(лись) подпись, которая не подошла ни к одному из файлов
class File extends \Exception
{
    use \Lib\Exceptions\Traits\MainTrait;
}

