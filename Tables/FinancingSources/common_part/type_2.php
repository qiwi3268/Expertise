<?php


namespace Tables\FinancingSources\common_part;

use Tables\FinancingSources\Interfaces\FinancingSourceTable;
use Tables\FinancingSources\Traits\Deleter;
use Tables\FinancingSources\Traits\FinancingSourceTable as FinancingSourceTableTrait;
use Tables\FinancingSources\Traits\type_2 as SelfTrait;


/**
 * Таблица: <i>'common_part_financing_source_type_2'</i>
 *
 * Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК
 *
 */
final class type_2 implements FinancingSourceTable
{

    static private string $tableName = 'common_part_financing_source_type_2';

    use Deleter;
    use FinancingSourceTableTrait;
    use SelfTrait;
}