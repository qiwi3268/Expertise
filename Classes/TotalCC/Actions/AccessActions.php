<?php


namespace Classes\TotalCC\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;

use core\Classes\Session;
use Lib\Actions\AccessActions as MainAccessActions;
use Tables\assigned_expert_total_cc;
use Tables\LoggingActions\total_cc as log_action_total_cc;



/**
 *  Предназначен для проверки доступа к действиям для типа документа <i>Сводное замечание / заключение</i>
 *
 * <b>**</b> В дочерних методах не надо реализовывать проверку на доступ к документу,
 * т.к. она должна выполняться на уровне route callbacks
 *
 */
class AccessActions extends MainAccessActions
{

    /**
     * Действие <i>Создать общую часть</i>
     *
     * @return bool
     * @throws DataBaseEx
     */
    public function action_1(): bool
    {

        // -------------------------Список условий-------------------------
        // Роли:    сотрудник экспертного отдела | сотрудник сметного отдела | внештатный эксперт
        // Стадия:  любая
        // Условия: - сотрудник один из тех, кто назначен на общую часть
        //          - текущее действие (Создать общую часть) не производилось
        // ----------------------------------------------------------------

        if (!log_action_total_cc::checkExistByIdMainDocumentAndActionCallbackName(
            CURRENT_DOCUMENT_ID,
            __FUNCTION__
        )) {
            return true;
        }
        return false;

        // -----------------------------------------------------------------------------------------


        if (
            Session::isEmpExp()
            || Session::isEmpEst()
            || Session::isFreExp()
        ) {

            if (assigned_expert_total_cc::checkCommonPartByIdTotalCCAndIdExpert(CURRENT_DOCUMENT_ID, Session::getUserId())) {

                if (!log_action_total_cc::checkExistByIdMainDocumentAndActionCallbackName(
                    CURRENT_DOCUMENT_ID,
                    __FUNCTION__
                )) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Действие <i>Редактировать общую часть</i>
     *
     * @return bool
     */
    public function action_2(): bool
    {

    }
}