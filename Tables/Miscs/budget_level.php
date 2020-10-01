<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\SingleMisc;
use Tables\Miscs\Interfaces\SingleMiscValidate;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'misc_budget_level'</i>
 *
 * Справочник "Уровень бюджета"
 *
 */
final class budget_level implements SingleMisc, SingleMiscValidate
{

    static private string $tableName = 'misc_budget_level';

    use Traits\SingleMisc;
    use ExistentTrait;
}
