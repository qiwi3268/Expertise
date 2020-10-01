<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\DependentMisc;
use Tables\Miscs\Interfaces\DependentMiscValidate;


/**
 * Таблица: <i>'misc_functional_purpose_subsector'</i>
 *
 * Справочник "Функциональное назначение. Подотрасль"
 *
 */
final class functional_purpose_subsector implements DependentMisc, DependentMiscValidate
{

    static private string $tableName = 'misc_functional_purpose_subsector';
    static private string $mainTableName = 'misc_functional_purpose';
    static private string $corrTableName = 'misc_functional_purpose_subsector_FOR_functional_purpose';

    use Traits\DependentMisc;
    use Traits\DependentMiscValidate;
}
