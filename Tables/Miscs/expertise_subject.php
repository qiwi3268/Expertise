<?php


namespace Tables\Miscs;


/**
 * Таблица: <i>'misc_expertise_subject'</i>
 *
 * Справочник "Предмет экспертизы"
 *
 */
final class expertise_subject implements Interfaces\DependentMiscValidate
{

    static private string $tableName = 'misc_expertise_subject';
    static private string $mainTableName = 'misc_expertise_purpose';
    static private string $corrTableName = 'misc_expertise_subject_FOR_expertise_purpose';

    use Traits\SingleMisc;
    use Traits\DependentMiscValidate;
}
