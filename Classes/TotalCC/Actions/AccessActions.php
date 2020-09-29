<?php


namespace Classes\TotalCC\Actions;

use core\Classes\Session;
use Lib\Actions\AccessActions as MainAccessActions;
use Tables\assigned_expert_total_cc;



/**
 *  Предназначен для проверки доступа к действиям для типа документа <i>Сводное замечание / заключение</i>
 *
 * <b>**</b> В дочерних методах не надо реализовывать проверку на доступ к документу,
 * т.к. она должна выполняться на уровне route callbacks
 *
 */
class AccessActions extends MainAccessActions
{

    // Реализация callback'ов доступа к действиям из БД

    /**
     * <b>Создать общую часть</b>
     *
     * @return bool
     */
    public function action_1(): bool
    {
        return true;
        // -------------------------Список условий-------------------------
        // Пользователь сотрудник экспертного отдела | сметного отдела | внештатный эксперт
        // Сотрудник один из тех, кто назначен на общую часть
        // Общая часть еще не создана
        // ----------------------------------------------------------------

        if (
            Session::isEmpExp()
            || Session::isEmpEst()
            || Session::isFreExp()
        ) {
            if (assigned_expert_total_cc::checkCommonPartByIdTotalCCAndIdExpert(CURRENT_DOCUMENT_ID, Session::getUserId())) {

                //todo проверка на несозданную общую часть
                return true;
            }
        }

        return false;
    }
}