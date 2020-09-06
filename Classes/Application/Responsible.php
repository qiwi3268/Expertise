<?php


namespace Classes\Application;

use Lib\Responsible\Responsible as MainResponsible;
use Tables\application;
use Tables\Responsible\type_3\application as resp_application_type_3;


class Responsible extends MainResponsible
{

    private int $applicationId;


    public function __construct(int $applicationId)
    {
        $this->applicationId = $applicationId;

        parent::__construct(application::getResponsibleTypeById($applicationId));
    }


    // Предназначен для удаления текущих ответственных с документа
    //
    public function deleteCurrentResponsible(): void
    {
        switch ($this->currentResponsibleType) {

            case self::RT['type_1']:
                break;
            case self::RT['type_2']:
                //todo 1
                break;
            case self::RT['type_3']:
                resp_application_type_3::deleteResponsible($this->applicationId);
                break;
            case self::RT['type_4']:
                //todo 2
                break;
        }
        //todo устанавливать тип ответственных в новом документе type_1

    }



    public function createNewResponsibleType3(string ...$accessGroupNames): array
    {
        // Удаляем текущие ответственные группы заявителей
        $this->deleteCurrentResponsible();

        $ids = [];
        $accessGroupIds = [];

        // Проверяем корректность принятых названий
        foreach ($accessGroupNames as $accessGroupName) {
            $accessGroupIds[] = $this->getApplicantAccessGroupId($accessGroupName);
        }

        // Создаем новые ответственные группы заявителей
        foreach ($accessGroupIds as $id) {

            $ids[] = resp_application_type_3::create(
                $this->applicationId,
                $id
            );
        }

        $responsibleType = self::RT['type_3'];

        // Обновляем информацию о типе ответственных в классе и в заявлении
        if ($this->currentResponsibleType != $responsibleType) {

            $this->currentResponsibleType = $responsibleType;
            application::updateResponsibleTypeById($this->applicationId, $responsibleType);
        }
        return $ids;
    }


    public function getResponsible(): array
    {

        $type = $this->currentResponsibleType;

        $result['type'] = $type;

        switch ($type) {
            case self::RT['type_1']:
                $result['users'] = null;
                break;
            case self::RT['type_2']:
                //todo 1
                break;
            case self::RT['type_3']:
                $result['users'] = resp_application_type_3::getResponsible($this->applicationId);
                break;
            case self::RT['type_4']:
                //todo 2
                break;
        }

        return $result;
    }
}