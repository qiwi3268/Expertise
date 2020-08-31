<?php


namespace Tables\Miscs;


// Справочник "Функциональное назначение"
//
final class functional_purpose implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_functional_purpose';

    use Traits\SingleMisc;
    use Traits\SingleMiscValidate;
}
