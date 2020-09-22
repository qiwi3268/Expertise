<?php


namespace Tables\Files;

use Tables\CommonInterfaces\Existent;
use Tables\CommonTraits\Existent as ExistentTrait;



/**
 * Таблица: <i>'file_grbs'</i>
 *
 */
final class grbs implements Existent, Interfaces\FileTableType1
{

    static private string $tableName = 'file_grbs';

    use ExistentTrait;
    use Traits\FileTable;
    use Traits\FileTableType1;
}