<?php


namespace Tables\Miscs;


// Справочник "Национальный проект"
//
final class national_project implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_national_project';

    use Traits\SingleMisc;
    use Traits\SingleMiscValidate;
}
