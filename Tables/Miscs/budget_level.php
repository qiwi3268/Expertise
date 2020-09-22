<?php


namespace Tables\Miscs;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'misc_budget_level'</i>
 *
 * Справочник "Уровень бюджета"
 *
 */
final class budget_level implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_budget_level';

    use Traits\SingleMisc;
    use ExistentTrait;
}
