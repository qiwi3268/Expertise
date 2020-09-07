<?php


namespace Lib\Responsible;


abstract class Responsible
{

    // Типы групп доступа к документу (Заявление / ...)
    // (RT - Responsible Type)
    protected const RT = [
        'type_1' => 'type_1', // Никто
        'type_2' => 'type_2', // Роли
        'type_3' => 'type_3', // Группы заявителей
        'type_4' => 'type_4'  // Пользователи
    ];


    // Виды групп доступа заявлителей к документу (Заявление / ...)
    // Ключ   - название группы доступа заявителя к заявлению
    // Значен - id группы доступа из БД
    private const APPLICANT_ACCESS_GROUP = [
        'full_access'                 => 1,
        'signing_financial_documents' => 2,
        'work_with_comments'          => 3,
        'only_view'                   => 4
    ];

    // Текущий тип ответственных
    protected string $currentResponsibleType;


    protected function __construct(string $currentResponsibleType)
    {
        // Проверяем, что с БД пришел известный тип ответственных
        $this->validateResponsibleType($currentResponsibleType);

        $this->currentResponsibleType = $currentResponsibleType;
    }


    private function validateResponsibleType(string $type): void
    {
        if (!isset(self::RT[$type])) {
            throw new \LogicException("Получен неизвестный тип ответственных: '{$type}'");
        }
    }


    protected function getApplicantAccessGroupId(string $accessGroupName): int
    {
        if (!isset(self::APPLICANT_ACCESS_GROUP[$accessGroupName])) {
            throw new \LogicException("Не существует указанного названия группы доступа заявителя к заявлению: '{$accessGroupName}'");
        }
        return self::APPLICANT_ACCESS_GROUP[$accessGroupName];
    }



    abstract public function deleteCurrentResponsible(): void;

    // *** В ходе своей работы метод должен:
    //        1) удалить текущих ответственных с главного документа
    //        2) создать новых ответственных
    //        3) обновить информацию о типе ответственных в классе (currentResponsibleType) и в главном документе
    //
    abstract public function createNewResponsibleType3(string ...$accessGroupNames): array;

    abstract public function getResponsible(): array;
}