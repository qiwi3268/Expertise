<?php


namespace Classes\TotalCC\Actions;

use Lib\Actions\ExecutionActions as MainExecutionActions;


class ExecutionActions extends MainExecutionActions
{

    // Реализация callback'ов исполнения действий из БД

    // Ошибкам во время исполнения действия необходимо присваивать code 6
    public function action_1(): string
    {
        return true;
    }
}