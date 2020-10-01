<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\DependentMisc;
use Tables\Miscs\Interfaces\DependentMiscValidate;


/**
 * Таблица: <i>'misc_federal_project'</i>
 *
 * Справочник "Федеральный проект"
 *
 */
final class federal_project implements DependentMisc, DependentMiscValidate
{

    static private string $tableName = 'misc_federal_project';
    static private string $mainTableName = 'misc_national_project';
    static private string $corrTableName = 'misc_federal_project_FOR_national_project';

    use Traits\DependentMisc;
    use Traits\DependentMiscValidate;
}
