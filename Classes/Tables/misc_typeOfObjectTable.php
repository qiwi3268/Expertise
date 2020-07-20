<?php

// Справочник "Вид объекта"
//
final class misc_typeOfObjectTable implements Interface_singleMiscTableValidate{

    static private string $tableName = 'misc_type_of_object';

    use Trait_singleMiscTableValidate;
    // checkExistById(int $id):bool

    use Trait_singleMiscTable;
    // getAllActive():array
}
