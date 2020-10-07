<?php


namespace Tables\LoggingActions;


/**
 * Таблица: <i>'log_action_section_documentation_2'</i>
 *
 */
class section_documentation_2 implements Interfaces\LogActionTable
{

    static private string $tableName = 'log_action_section_documentation_2';
    static private string $actionTableName = 'action_section_documentation_2';

    use Traits\LogActionTable;
}