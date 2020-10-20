<?php


namespace Tables\Responsible\type_2;

use Tables\Responsible\type_2\Interfaces\ResponsibleType2;
use Tables\Responsible\type_2\Traits\ResponsibleType2 as ResponsibleType2Trait;


/**
 * Таблица: <i>'resp_total_cc_type_2'</i>
 *
 * Ответственные роли к сводному замечанию / заключению
 *
 */
final class total_cc implements ResponsibleType2
{

    static private string $tableName = 'resp_total_cc_type_2';

    use ResponsibleType2Trait;
}



