<?php


namespace Tables\Miscs;

use Tables\CommonTraits\Existent as ExistentTrait;



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
    use ExistentTrait;
}
