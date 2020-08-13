<?php


class file_grbsTable implements Interface_fileTableType1{

    static private string $tableName = 'file_grbs';
    
    use Trait_fileTable;
    
    use Trait_fileTableType1;
    // create(int $id_application, string $file_name, string $hash):int
}