<?php


namespace Tables\LoggingActions;


/**
 * Таблица: <i>'log_action_total_cc'</i>
 *
 */
class total_cc implements Interfaces\LogActionTable
{

    static private string $tableName = 'log_action_total_cc';
    static private string $actionTableName = 'action_total_cc';

    use Traits\LogActionTable;
}