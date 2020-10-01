<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\SingleMisc;
use Tables\Miscs\Interfaces\SingleMiscValidate;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'misc_curator'</i>
 *
 * Справочник "Куратор"
 *
 */
final class curator implements SingleMisc, SingleMiscValidate
{

    static private string $tableName = 'misc_curator';

    use Traits\SingleMisc;
    use ExistentTrait;
}
