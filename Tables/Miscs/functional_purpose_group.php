<?php


namespace Tables\Miscs;


/**
 * Таблица: <i>'misc_functional_purpose_group'</i>
 *
 * Справочник "Функциональное назначение. Группа"
 *
 */
final class functional_purpose_group implements Interfaces\DependentMiscValidate
{

    static private string $tableName = 'misc_functional_purpose_group';
    static private string $mainTableName = 'misc_functional_purpose_subsector';
    static private string $corrTableName = 'misc_functional_purpose_group_FOR_functional_purpose_subsector';

    use Traits\DependentMiscValidate;
}
