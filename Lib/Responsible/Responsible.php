<?php


namespace Lib\Responsible;

use Lib\Exceptions\Responsible as SelfEx;


/**
 * Предназначен для работы с ответственными
 *
 */
class Responsible
{

    /**
     * Виды групп доступа заявлителей к документу (Заявление / ...)
     *
     * <i>Ключ</i> - название группы доступа заявителя к заявлению<br>
     * <i>Значение</i> - id группы доступа из БД
     *
     */
    private const APPLICANT_ACCESS_GROUP = [
        'full_access'                 => 1,
        'signing_financial_documents' => 2,
        'work_with_comments'          => 3,
        'only_view'                   => 4
    ];

    static private XMLReader $XMLReader;

    /**
     * Кеширование ответственных
     *
     */
    static private array $cacheResponsible = [
        'application' => [
            'type_2' => null,
            'type_3' => null,
            'type_4' => null
        ]
    ];

    /**
     * Текущий тип ответственных
     *
     */
    private string $currentResponsibleType;

    /**
     * id документа
     *
     */
    private int $documentId;

    /**
     * Текущий тип документа
     *
     */
    private string $currentDocumentType;


    /**
     * Конструктор класса
     *
     * @param int $documentId id документа
     * @param string $documentType тип документа, согласно константе DOCUMENT_TYPE
     * @throws SelfEx
     */
    public function __construct(int $documentId, string $documentType)
    {
        if (!isset(DOCUMENT_TYPE[$documentType])) {
            throw new SelfEx("Передан некорректный тип документа: '{$documentType}'", 1);
        }

        if (!isset(self::$XMLReader)) {
            self::$XMLReader = new XMLReader();
        }

        // Метод получения типа ответственных
        list('class' => $class, 'method' => $method) = self::$XMLReader->getResponsibleType($documentType);

        $this->currentResponsibleType = $class::$method($documentId);
        $this->documentId = $documentId;
        $this->currentDocumentType = $documentType;
    }


    /**
     * Предназначен для получения текущих ответственных
     *
     *
     * @return array массив формата:<br>
     * 'type'  => тип ответственных<br>
     * 'users' => ?array : null / ассоциативные массивы ответственных пользователей
     * @throws SelfEx
     */
    public function getCurrentResponsible(): array
    {
        if ($this->currentResponsibleType == 'type_1') {

            $responsible = null;
        } else {

            if (isset(self::$cacheResponsible[$this->currentDocumentType][$this->currentResponsibleType])) {

                // Берем ответственных из кэша
                $responsible = self::$cacheResponsible[$this->currentDocumentType][$this->currentResponsibleType];
            } else {

                // Метод получения ответственных
                list('class' => $class, 'method' => $method) = self::$XMLReader->getResponsible(
                    $this->currentDocumentType,
                    $this->currentResponsibleType
                );

                $responsible = $class::$method($this->documentId);

                // Добавляем ответственных в кэш
                self::$cacheResponsible[$this->currentDocumentType][$this->currentResponsibleType] = $responsible;
            }
        }

        return [
            'type'  => $this->currentResponsibleType,
            'users' => $responsible
        ];
    }


    /**
     * Предназначен для удаления текущих ответственных
     *
     * @param bool $needUpdateResponsibleType нужно ли обновлять тип ответственных в текущем документе на type_1
     * @throws SelfEx
     */
    public function deleteCurrentResponsible(bool $needUpdateResponsibleType = true): void
    {
        if ($this->currentResponsibleType == 'type_1') {
            return;
        }

        // Метод удаления ответственных
        list('class' => $class, 'method' => $method) = self::$XMLReader->deleteResponsible(
            $this->currentDocumentType,
            $this->currentResponsibleType
        );
        $class::$method($this->documentId);

        // Устанавливаем в главном документе тип ответственных "Никто"
        if ($needUpdateResponsibleType) {

            list('class' => $class, 'method' => $method) = self::$XMLReader->updateResponsibleType($this->currentDocumentType);
            $class::$method($this->documentId, 'type_1');
        }

        // Обновляем кэш
        self::$cacheResponsible[$this->currentDocumentType][$this->currentResponsibleType] = null;
        // Обновляем текущий тип ответственных у документа
        $this->currentResponsibleType = 'type_1';
    }


    /**
     * Предназначен для создания новых ответственных "Ответственные группы заявителей"
     *
     * <b>*</b> Перед использованием метода не требуется вручную удалять текущих ответственных
     *
     * @param string ...$accessGroupNames <i>перечисление</i> названий групп заявителей (согласно константе APPLICANT_ACCESS_GROUP)
     * @return array индексный массив id созданных записей ответственных групп заявителей
     * @throws SelfEx
     */
    public function createNewResponsibleType3(string ...$accessGroupNames): array
    {
        // Удаляем текущие ответственные группы заявителей
        $this->deleteCurrentResponsible(false);

        $accessGroupIds = [];

        // Получение id групп ответственных из принятых названий
        foreach ($accessGroupNames as $accessGroupName) {

            if (!isset(self::APPLICANT_ACCESS_GROUP[$accessGroupName])) {
                throw new SelfEx("Не существует указанного названия группы доступа заявителя к заявлению: '{$accessGroupName}'", 2);
            }
            $accessGroupIds[] = self::APPLICANT_ACCESS_GROUP[$accessGroupName];
        }

        // Метод создания ответственных
        list('class' => $class, 'method' => $method) = self::$XMLReader->createResponsible(
            $this->currentDocumentType,
            'type_3'
        );

        $ids = [];

        foreach ($accessGroupIds as $id) {
            $ids[] = $class::$method($this->documentId, $id);
        }

        // Обновляем текущий тип ответственных у документа в БД и классе
        if ($this->currentResponsibleType != 'type_3') {

            // Метод обновления типа ответственных
            list('class' => $class, 'method' => $method) = self::$XMLReader->updateResponsibleType($this->currentDocumentType);

            $class::$method($this->documentId, 'type_3');
            $this->currentResponsibleType = 'type_3';
        }

        return $ids;
    }


    /**
     * Предназначен для проверки пользователя на принадлежность к ответственным
     *
     * @param int $userId id пользователя
     * @return bool <b>true</b> пользователь является ответственным<br><b>false</b> в противном случае
     * @throws SelfEx
     */
    public function isUserResponsible(int $userId): bool
    {
        list('type' => $type, 'users' => $users) = $this->getCurrentResponsible();

        switch ($type) {
            case 'type_1' :
                return false;
            case 'type_2' :
                //todo
            case 'type_3' :

                if (is_null($users)) {

                    return false;
                } else {

                    foreach ($users as $user) if ($user['user_id'] == $userId) return true;
                    return false;
                }
            case 'type_4' :
                //todo
        }
    }
}
