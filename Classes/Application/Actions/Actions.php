<?php


namespace Classes\Application\Actions;
use Lib\Actions\Actions as MainActions;
use Lib\Actions\AccessActions as MainAccessActions;
use Lib\Actions\ExecutionActions as MainExecutionActions;
use Tables\Actions\application as ActionTable;


class Actions extends MainActions
{
    public function getAssocActiveActions(): array
    {
        return ActionTable::getAllActive();
    }

    public function getAssocActiveActionByPageName(string $pageName): ?array
    {
        return ActionTable::getAssocByPageName($pageName);
    }

    public function getAssocActionByHash(string $hash): ?array
    {
        return ActionTable::getAssocByHash($hash);
    }

    public function getAccessActions(): MainAccessActions
    {
        return new AccessActions($this);
    }

    public function getExecutionActions(): MainExecutionActions
    {
        return new ExecutionActions($this);
    }
}