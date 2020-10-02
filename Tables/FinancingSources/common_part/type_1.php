<?php


namespace Tables\FinancingSources\common_part;

use Tables\FinancingSources\Interfaces\FinancingSourceTable;
use Tables\FinancingSources\Traits\Deleter;
use Tables\FinancingSources\Traits\type_1 as SelfTrait;


/**
 * Таблица: <i>'common_part_financing_source_type_1'</i>
 *
 * Бюджетные средства
 *
 */
final class type_1 implements FinancingSourceTable
{

    static private string $tableName = 'common_part_financing_source_type_1';

    use Deleter;
    use SelfTrait;
}
