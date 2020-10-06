<?php


namespace Classes\Section\Actions;
use Lib\Actions\AccessActions as MainAccessActions;


/**
 *  Предназначен для проверки доступа к действиям для типа документа <i>Раздел</i>
 *
 * <b>**</b> В дочерних методах не надо реализовывать проверку на доступ к документу,
 * т.к. она должна выполняться на уровне route callbacks
 *
 */
class AccessActions extends MainAccessActions
{

    public function action_1(): bool
    {
        return true;
    }
}