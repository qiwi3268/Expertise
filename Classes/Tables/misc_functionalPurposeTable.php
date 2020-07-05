<?php


// Справочник "Функциональное назначение"
//
final class misc_functionalPurposeTable implements Interface_singleMiscTableValidate{

    static private string $tableName = 'misc_functional_purpose';

    use Trait_singleMiscTableValidate;
    // checkExistById(int $id):bool

    use Trait_singleMiscTable;
    // getAllActive():array
}
