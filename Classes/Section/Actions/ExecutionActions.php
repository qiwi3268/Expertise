<?php


namespace Classes\Section\Actions;

use Lib\Actions\ExecutionActions as MainExecutionActions;


/**
 *  Предназначен для исполнения действий для типа документа <i>Раздел</i>
 *
 */
class ExecutionActions extends MainExecutionActions
{

    public function action_1(): string
    {
        return true;
    }
}