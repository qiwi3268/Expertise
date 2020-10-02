<?php


namespace Tables\FinancingSources\application;

use Tables\FinancingSources\Interfaces\FinancingSourceTable;
use Tables\FinancingSources\Traits\Deleter;
use Tables\FinancingSources\Traits\FinancingSourceTable as FinancingSourceTableTrait;
use Tables\FinancingSources\Traits\type_3 as SelfTrait;


/**
 * Таблица: <i>'application_financing_source_type_3'</i>
 *
 * Собственные средства застройщика
 *
 */
final class type_3 implements FinancingSourceTable
{

    static private string $tableName = 'application_financing_source_type_3';

    use Deleter;
    use FinancingSourceTableTrait;
    use SelfTrait;
}