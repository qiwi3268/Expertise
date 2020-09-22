<?php


namespace Tables\Files;

use Tables\CommonInterfaces\Existent;
use Tables\CommonTraits\Existent as ExistentTrait;



/**
 * Таблица: <i>'file_documentation_2'</i>
 *
 */
final class documentation_2 implements Existent, Interfaces\FileTableType2
{

    static private string $tableName = 'file_documentation_2';

    use ExistentTrait;
    use Traits\FileTable;
    use Traits\FileTableType2;
}
