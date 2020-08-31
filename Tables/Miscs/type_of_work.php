<?php


namespace Tables\Miscs;


// Справочник "Вид работ"
//
final class type_of_work implements Interfaces\DependentMiscValidate
{

    static private string $tableName = 'misc_type_of_work';
    static private string $mainTableName = 'misc_expertise_purpose';
    static private string $corrTableName = 'misc_type_of_work_FOR_expertise_purpose';

    use Traits\DependentMiscValidate;
}
