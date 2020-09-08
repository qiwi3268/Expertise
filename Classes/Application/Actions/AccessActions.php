<?php


namespace Classes\Application\Actions;
use Lib\Actions\AccessActions as MainAccessActions;


class AccessActions extends MainAccessActions
{
    public function action_1(): bool
    {
        return true;
    }

    public function action_2(): bool
    {
        return false;
    }
}