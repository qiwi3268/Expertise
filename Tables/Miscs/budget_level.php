<?php


namespace Tables\Miscs;


// Справочник "Уровень бюджета"
//
final class budget_level implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_budget_level';

    use Traits\SingleMisc;
    use Traits\SingleMiscValidate;
}
