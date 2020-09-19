<?php


namespace Tables\Responsible\type_4;

use Tables\Responsible\type_4\Interfaces\ResponsibleType4;
use Tables\Responsible\type_4\Traits\ResponsibleType4 as ResponsibleType4Trait;

/**
 * Таблица: <i>'resp_total_cc_type_4'</i>
 *
 * Ответственные пользователи к сводному замечанию / заключению
 *
 */
final class total_cc implements ResponsibleType4
{

    static private string $tableName = 'resp_total_cc_type_4';

    use ResponsibleType4Trait;
}