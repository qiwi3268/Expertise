<?php


namespace Tables\Miscs;


/**
 * Таблица: <i>'misc_functional_purpose'</i>
 *
 * Справочник "Функциональное назначение"
 *
 */
final class functional_purpose implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_functional_purpose';

    use Traits\SingleMisc;
    use Traits\SingleMiscValidate;
}
