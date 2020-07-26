<?php


class file_documentation1Table implements Interface_fileTableType2{

    static private string $tableName = 'file_documentation_1';
    
    use Trait_fileTable;
    // deleteById(int $id):void
    // setUploadedById(int $id):void
    // getAssocById(int $id):?array
    // getNoNeedsAssoc():?array
    // setCronDeletedFlagById(int $id):void

    use Trait_fileTableType2;
    // create(int $id_application, int $id_structure_node, string $file_name, string $hash):int
}
