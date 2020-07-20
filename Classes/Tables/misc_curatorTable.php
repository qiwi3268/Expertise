<?php


// Справочник "Куратор"
//
final class misc_curatorTable implements Interface_singleMiscTableValidate{

    static private string $tableName = 'misc_curator';

    use Trait_singleMiscTableValidate;
    // checkExistById(int $id):bool

    use Trait_singleMiscTable;
    // getAllActive():array
}
