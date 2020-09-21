<?php


namespace Tables\Files;


/**
 * Таблица: <i>'file_documentation_2'</i>
 *
 */
final class documentation_2 implements Interfaces\FileTableType2
{

    static private string $tableName = 'file_documentation_2';

    use Traits\FileTable;
    use Traits\FileTableType2;
}
