<?php


namespace Classes\Application\Actions;
use Lib\Actions\AccessActions as MainAccessActions;


/**
 *  Предназначен для проверки доступа к действиям для типа документа <i>Заявление</i>
 *
 * <b>**</b> В дочерних методах не надо реализовывать проверку на доступ к документу,
 * т.к. она должна выполняться на уровне route callbacks
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