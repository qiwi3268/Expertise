<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\SingleMisc;
use Tables\Miscs\Interfaces\SingleMiscValidate;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'misc_comment_criticality'</i>
 *
 * Справочник "Критичность замечания"
 *
 */
final class comment_criticality implements SingleMisc, SingleMiscValidate
{

    static private string $tableName = 'misc_comment_criticality';

    use Traits\SingleMisc;
    use ExistentTrait;
}
