<?php


class file_documentation2Table implements Interface_fileTableType2{

    static private string $tableName = 'file_documentation_2';
    
    use Trait_fileTable;

    use Trait_fileTableType2;
    // create(int $id_application, int $id_structure_node, string $file_name, string $hash):int
}
