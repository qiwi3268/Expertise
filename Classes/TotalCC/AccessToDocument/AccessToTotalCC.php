<?php


namespace Classes\TotalCC\AccessToDocument;

use Lib\Exceptions\AccessToDocument as SelfEx;
use Lib\Exceptions\DataBase;
use Lib\Exceptions\DataBase as DataBaseEx;

use core\Classes\Session;
use Lib\AccessToDocument\AccessToDocument;
use Tables\Docs\total_cc;


/**
 * Предназначен для проверки доступа пользователя к сводному замечанию / заключению
 *
 */
class AccessToTotalCC extends AccessToDocument
{


    /**
     * Реализация абстрактного метода
     *
     * <b>*</b> Использование метода подразумевает, что пользователь имеет доступ
     * к заявлению
     *
     * @throws SelfEx
     * @throws DataBaseEx
     */
    public function checkAccess(): void
    {
        if (Session::isApplicant()) {

            // Для заявителя происходит проверка стадии документа. Он должен быть выдан
            $stageId = total_cc::getIdStageById($this->documentId);

            // Массив id стадий сводного замечания / заключения, на которых доступ у заявителя отсутствует
            // 1 - Подготовка сводного замечания экспертами
            $deniedStagesId = [1];

            if (in_array($stageId, $deniedStagesId)) {
                throw new SelfEx('Отсутствует доступ к документу "Сводное замечание / заключение" для заявителя', 6);
            }

        }
        // Для остальных пользователей доступ к сводному замечанию / заключению открыт
    }
}