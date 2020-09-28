<?php


namespace Classes\Application\AccessToDocument;

use Lib\Exceptions\AccessToDocument as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;

use core\Classes\Session;
use Lib\AccessToDocument\AccessToDocument;
use Tables\applicant_access_group;
use Tables\Docs\Relations\total_cc;
use Tables\assigned_expert_total_cc;


/**
 * Предназначен для проверки доступа пользователя к заявлению
 *
 */
class AccessToApplication extends AccessToDocument
{

    private ?int $totalCCId;


    /**
     * Конструктор класса
     *
     * @param int $documentId
     * @param int|null $totalCCId <b>-1</b> если предварительное получение id сводного замечания не проводилось<br>
     * <b>положительное число</b> id, проверка проводилась, документ существует<br>
     * <b>null</b> проверка проводилась, документ не существует
     *
     */
    public function __construct(int $documentId, ?int $totalCCId = -1)
    {
        parent::__construct($documentId);

        $this->totalCCId = $totalCCId;
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws SelfEx
     * @throws DataBaseEx
     */
    public function checkAccess(): void
    {
        if (Session::isApplicant()) {

            // Для заявителя происходит проверка, что он состоит в любой группе доступа к заявлению
            if (!applicant_access_group::checkExistByIdApplicationAndIdUser($this->documentId, Session::getUserId())) {
                throw new SelfEx('Отсутствует доступ к документу "Заявление" для заявителя', 3);
            }

        } elseif (Session::isFreelanceExpert()) {

            $totalCCId = $this->totalCCId;

            // Для внештатного эксперта происходит проверка, что он назначен на сводное замечание / заключение
            if (
                is_null($totalCCId)
                || (
                    $totalCCId  === -1
                    && is_null($totalCCId = total_cc::getIdByIdMainDocument($this->documentId))
                )
            ) {
                throw new SelfEx('Отсутствует доступ к документу "Заявление" для внештатного эксперта', 4);
            }

            if (!assigned_expert_total_cc::checkExistByIdTotalCCAndIdExpert($totalCCId, Session::getUserId())) {
                throw new SelfEx('Отсутствует доступ к документу "Заявление" для внештатного эксперта', 5);
            }
        }
        // Для остальных пользователей доступ к заявлению открыт
    }
}