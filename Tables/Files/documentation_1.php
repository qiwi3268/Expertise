<?php


namespace Tables\Files;


/**
 * Таблица: <i>'file_documentation_1'</i>
 *
 */
final class documentation_1 implements Interfaces\FileTableType2
{

    static private string $tableName = 'file_documentation_1';

    use Traits\FileTable;
    use Traits\FileTableType2;
}
