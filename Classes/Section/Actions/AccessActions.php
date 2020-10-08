<?php


namespace Classes\Section\Actions;
use core\Classes\Session;
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

    /**
     * Действие <i>Создать описательную часть</i>
     *
     * @return bool
     */
    public function action_1(): bool
    {
        return true;

        // -------------------------Список условий-------------------------
        // Роли:    сотрудник экспертного отдела | сотрудник сметного отдела | внештатный эксперт
        // Стадия:  "Подготовка раздела"
        // Условия: - текущий пользователь не создавал описательную часть
        // ----------------------------------------------------------------

        $docTable = $this->actions->documentTypeTableLocator->getDocs();

        // -----------------------------------------------------------------------------------------
        if (
            Session::isEmpExp()
            || Session::isEmpEst()
            || Session::isFreExp()
        ) {


            return true;
        }
        return false;
    }
}