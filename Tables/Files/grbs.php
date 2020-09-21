<?php


namespace Tables\Files;


/**
 * Таблица: <i>'file_grbs'</i>
 *
 */
final class grbs implements Interfaces\FileTableType1
{

    static private string $tableName = 'file_grbs';

    use Traits\FileTable;
    use Traits\FileTableType1;
}