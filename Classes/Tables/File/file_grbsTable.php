<?php


class file_grbsTable implements \Lib\Files\Interfaces\FileTableType1{

    static private string $tableName = 'file_grbs';
    
    use Trait_fileTable;
    
    use Trait_fileTableType1;
}