<?php


namespace Tables\LoggingActions;


/**
 * Таблица: <i>'log_action_application'</i>
 *
 */
class application implements Interfaces\LogActionTable
{

    static private string $tableName = 'log_action_application';
    static private string $actionTableName = 'action_application';

    use Traits\LogActionTable;
}