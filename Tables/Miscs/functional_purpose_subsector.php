<?php


namespace Tables\Miscs;


// Справочник "Функциональное назначение. Подотрасль"
//
final class functional_purpose_subsector implements Interfaces\DependentMiscValidate
{

    static private string $tableName = 'misc_functional_purpose_subsector';
    static private string $mainTableName = 'misc_functional_purpose';
    static private string $corrTableName = 'misc_functional_purpose_subsector_FOR_functional_purpose';

    use Traits\SingleMisc;
    use Traits\DependentMiscValidate;
}
