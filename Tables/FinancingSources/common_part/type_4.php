<?php


namespace Tables\FinancingSources\common_part;

use Tables\FinancingSources\Interfaces\FinancingSourceTable;
use Tables\FinancingSources\Traits\Deleter;
use Tables\FinancingSources\Traits\FinancingSourceTable as FinancingSourceTableTrait;
use Tables\FinancingSources\Traits\type_4 as SelfTrait;


/**
 * Таблица: <i>'common_part_financing_source_type_4'</i>
 *
 * Средства инвестора
 *
 */
final class type_4 implements FinancingSourceTable
{

    static private string $tableName = 'common_part_financing_source_type_4';

    use Deleter;
    use FinancingSourceTableTrait;
    use SelfTrait;
}