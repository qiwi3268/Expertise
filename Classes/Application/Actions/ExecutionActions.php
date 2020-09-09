<?php


namespace Classes\Application\Actions;
use Lib\Actions\ExecutionActions as MainExecutionActions;


class ExecutionActions extends MainExecutionActions
{
    public function action_1(): string
    {
        return true;
    }

    public function action_2(): string
    {
        return true;
    }
}