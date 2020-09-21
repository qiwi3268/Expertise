<?php


namespace Tables\Miscs;


/**
 * Таблица: <i>'misc_curator'</i>
 *
 * Справочник "Куратор"
 *
 */
final class curator implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_curator';

    use Traits\SingleMisc;
    use Traits\SingleMiscValidate;
}
