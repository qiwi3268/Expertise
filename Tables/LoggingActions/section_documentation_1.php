<?php


namespace Tables\LoggingActions;


/**
 * Таблица: <i>'log_action_section_documentation_1'</i>
 *
 */
class section_documentation_1 implements Interfaces\LogActionTable
{

    static private string $tableName = 'log_action_section_documentation_1';
    static private string $actionTableName = 'action_section_documentation_1';

    use Traits\LogActionTable;
}