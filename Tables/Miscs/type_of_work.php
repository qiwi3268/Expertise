<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\DependentMisc;
use Tables\Miscs\Interfaces\DependentMiscValidate;


/**
 * Таблица: <i>'misc_type_of_work'</i>
 *
 * Справочник "Вид работ"
 *
 */
final class type_of_work implements DependentMisc, DependentMiscValidate
{

    static private string $tableName = 'misc_type_of_work';
    static private string $mainTableName = 'misc_expertise_purpose';
    static private string $corrTableName = 'misc_type_of_work_FOR_expertise_purpose';

    use Traits\DependentMisc;
    use Traits\DependentMiscValidate;
}
