<?php


namespace Tables\Miscs;

use Lib\DataBase\ParametrizedQuery;


// Справочник "Цель обращения"
//
final class expertise_purpose implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_expertise_purpose';

    use Traits\SingleMisc;
    use Traits\SingleMiscValidate;
}
