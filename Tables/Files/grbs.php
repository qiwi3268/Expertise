<?php


namespace Tables\Files;


class grbs implements Interfaces\FileTableType1
{

    static private string $tableName = 'file_grbs';

    use Traits\FileTable;
    use Traits\FileTableType1;
}