<?php


namespace Tables\Miscs;

use Tables\CommonTraits\Existent as ExistentTrait;



/**
 * Таблица: <i>'misc_expertise_purpose'</i>
 *
 * Справочник "Цель обращения"
 *
 */
final class expertise_purpose implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_expertise_purpose';

    use Traits\SingleMisc;
    use ExistentTrait;
}
