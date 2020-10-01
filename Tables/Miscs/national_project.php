<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\SingleMisc;
use Tables\Miscs\Interfaces\SingleMiscValidate;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'misc_national_project'</i>
 *
 * Справочник "Национальный проект"
 *
 */
final class national_project implements SingleMisc, SingleMiscValidate
{

    static private string $tableName = 'misc_national_project';

    use Traits\SingleMisc;
    use ExistentTrait;
}
