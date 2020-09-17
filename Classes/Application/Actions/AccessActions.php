<?php


namespace Classes\Application\Actions;
use Lib\Actions\AccessActions as MainAccessActions;


/**
 *  Предназначен для проверки доступа к действиям для типа документа <i>Заявление</i>
 *
 */
class AccessActions extends MainAccessActions
{
    // Реализация callback'ов доступа к действиям из БД

    public function action_1(): bool
    {
        return true;
    }

    public function action_2(): bool
    {
        return true;
    }
}