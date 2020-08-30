<?php


class file_documentation2Table implements \Lib\Files\Interfaces\FileTableType2{

    static private string $tableName = 'file_documentation_2';
    
    use Trait_fileTable;

    use Trait_fileTableType2;
}
