<?php


namespace Classes\Application\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;

use core\Classes\Session;
use Lib\Actions\AccessActions as MainAccessActions;
use Tables\Docs\application;
use Tables\LoggingActions\application as log_action_application;


/**
 *  Предназначен для проверки доступа к действиям для типа документа <i>Заявление</i>
 *
 * <b>**</b> В дочерних методах не надо реализовывать проверку на доступ к документу,
 * т.к. она должна выполняться на уровне route callbacks
 *
 */
class AccessActions extends MainAccessActions
{


    /**
     * Действие <i>Передать на рассмотрение в ПТО</i>
     *
     * @return bool
     */
    public function action_1(): bool
    {
        // -------------------------Список условий-------------------------
        // Роли: todo
        // Стадия: "Оформление заявления"
        // Условия: todo
        // ----------------------------------------------------------------
        //todo условия - все обязательные поля в анкете заполнены и файлы подписаны корректными эцп
        return true;
    }


    /**
     * Действие <i>Назначить экспертов</i>
     *
     * @return bool
     * @throws DataBaseEx
     */
    public function action_2(): bool
    {
        // -------------------------Список условий-------------------------
        // Роли: сотрудник ПТО
        // Стадия: любая
        // Условия: - в анкете сохранен вид объекта
        //          - текущее действие (Назначить экспертов) не производилось
        // ----------------------------------------------------------------

        if (application::checkExistByIdWhereIdTypeOfObjectNN(CURRENT_DOCUMENT_ID)) {

            if (!log_action_application::checkExistByIdMainDocumentAndActionCallbackName(
                CURRENT_DOCUMENT_ID,
                __FUNCTION__
            )) {
                return true;
            }
        }
        return false;

        // -----------------------------------------------------------------------------------------

        if (Session::isEmpPTO()) {

            if (application::checkExistByIdWhereIdTypeOfObjectNN(CURRENT_DOCUMENT_ID)) {

                if (!log_action_application::checkExistByIdMainDocumentAndActionCallbackName(
                    CURRENT_DOCUMENT_ID,
                    __FUNCTION__
                )) {
                    return true;
                }
            }
        }
        return false;
    }
}