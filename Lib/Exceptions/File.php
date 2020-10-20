<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе файловых классов
 *
 * {@see \Lib\Files\Uploader}<br>
 * размерность массива uploadNames не соответствует имеющемуся количеству файлов<br>
 * {@see \Lib\Files\Mappings\RequiredMappingsSetter}<br>
 * запрашиваемый mapping_level_1 не существует в FILE_TABLE_MAPPING<br>
 * запрашиваемый mapping_level_2 не существует в mapping_level_1 в FILE_TABLE_MAPPING<br>
 * {@see \Lib\Files\Initialization\Initializer}<br>
 * ошибка в маппинг таблице (файлов)<br>
 * ошибка в маппинг таблице (подписей)<br>
 * метод: '...::getAllAssocWhereNeedsByIds' не смог выполнить добор недостающих файлов
 * метод: '...::getAllAssocWhereNeedsByIds' не смог выполнить недостающих файлов с id: ...
 * в массиве файлов не найден нужный id
 *
 */
class File extends \Exception
{
    use MainTrait;
}

