<?php


// Справочник "Национальный проект"
//
final class misc_nationalProjectTable implements Interface_singleMiscTableValidate{

    static private string $tableName = 'misc_national_project';

    use Trait_singleMiscTableValidate;
    // checkExistById(int $id):bool

    use Trait_singleMiscTable;
    // getAllActive():array
}
