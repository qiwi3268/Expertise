<?php


// Справочник "Тип объекта культурного наследия"
//
final class misc_culturalObjectTypeTable implements Interface_singleMiscTableValidate{

    static private string $tableName = 'misc_cultural_object_type';

    use Trait_singleMiscTableValidate;
    // checkExistById(int $id):bool

    use Trait_singleMiscTable;
    // getAllActive():array
}
