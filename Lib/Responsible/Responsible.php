<?php


namespace Lib\Responsible;

use Lib\Exceptions\Responsible as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use ReflectionException;
use Lib\DataBase\Transaction;


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
        ],
        'total_cc' => [
            'type_2' => null,
            'type_3' => null,
            'type_4' => null
        ],
        'section_documentation_1' => [
            'type_2' => null,
            'type_3' => null,
            'type_4' => null
        ],
        'section_documentation_2' => [
            'type_2' => null,
            'type_3' => null,
            'type_4' => null
        ],
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
        list(
            'class'  => $getResponsibleTypeClass,
            'method' => $getResponsibleTypeMethod
            ) = self::$XMLReader->getResponsibleType($documentType);

        $this->currentResponsibleType = $getResponsibleTypeClass::$getResponsibleTypeMethod($documentId);
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
                list(
                    'class'  => $getResponsibleClass,
                    'method' => $getResponsibleMethod
                    ) = self::$XMLReader->getResponsible($this->currentDocumentType, $this->currentResponsibleType);

                $responsible = $getResponsibleClass::$getResponsibleMethod($this->documentId);

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
     * Используется в клиентском коде
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
        list(
            'class'  => $deleteResponsibleClass,
            'method' => $deleteResponsibleMethod
            ) = self::$XMLReader->deleteResponsible($this->currentDocumentType, $this->currentResponsibleType);

        $deleteResponsibleClass::$deleteResponsibleMethod($this->documentId);

        // Устанавливаем в главном документе в БД тип ответственных "Никто"
        if ($needUpdateResponsibleType) {

            list(
                'class'  => $updateResponsibleTypeClass,
                'method' => $updateResponsibleTypeMethod
                ) = self::$XMLReader->updateResponsibleType($this->currentDocumentType);

            $updateResponsibleTypeClass::$updateResponsibleTypeMethod($this->documentId, 'type_1');
        }

        // Обновляем кэш (обнуляем ответственных из того типа, который удалили)
        self::$cacheResponsible[$this->currentDocumentType][$this->currentResponsibleType] = null;

        $this->currentResponsibleType = 'type_1';
    }


    /**
     * Предназначен для добавления к транзакции метода удаления текущих ответственных
     *
     * Используется в методах createNewResponsible этого класса
     *
     * @param Transaction $transaction
     * @throws SelfEx
     * @throws TransactionEx
     * @throws ReflectionException
     */
    private function addDeletionCurrentResponsibleToTransaction(Transaction $transaction): void
    {
        if ($this->currentResponsibleType != 'type_1') {

            // Метод удаления ответственных
            list(
                'class'  => $deleteResponsibleClass,
                'method' => $deleteResponsibleMethod
                ) = self::$XMLReader->deleteResponsible($this->currentDocumentType, $this->currentResponsibleType);

            // Обновляем кэш (обнуляем ответственных из того типа, который добавили в транзакцию)
            self::$cacheResponsible[$this->currentDocumentType][$this->currentResponsibleType] = null;

            $transaction->add($deleteResponsibleClass, $deleteResponsibleMethod, [$this->documentId]);
        }
    }


    /**
     * Предназначен для создания новых ответственных "Ответственные группы заявителей"
     *
     * <b>*</b> Перед использованием метода не требуется вручную удалять текущих ответственных
     *
     * @param string[] $accessGroupNames индексный массив названий групп ответственных заявителей
     * @return array индексный массив id созданных записей ответственных групп заявителей
     * @throws DataBaseEx
     * @throws ReflectionException
     * @throws SelfEx
     * @throws TransactionEx
     */
    public function createNewResponsibleType3(array $accessGroupNames): array
    {
        $transaction = new Transaction();

        // Добавляем удаление текущих ответственных в траназакцию
        $this->addDeletionCurrentResponsibleToTransaction($transaction);

        // Получение id групп ответственных из принятых названий
        $accessGroupIds = [];
        foreach ($accessGroupNames as $accessGroupName) {

            if (!isset(self::APPLICANT_ACCESS_GROUP[$accessGroupName])) {
                throw new SelfEx("Не существует указанного названия группы доступа заявителя к заявлению: '{$accessGroupName}'", 2);
            }
            $accessGroupIds[] = self::APPLICANT_ACCESS_GROUP[$accessGroupName];
        }

        // Метод создания ответственных
        list(
            'class'  => $createResponsibleClass,
            'method' => $createResponsibleMethod
            ) = self::$XMLReader->createResponsible($this->currentDocumentType, 'type_3');

        foreach ($accessGroupIds as $id) $transaction->add($createResponsibleClass, $createResponsibleMethod, [$this->documentId, $id]);

        // Обновляем текущий тип ответственных у документа в БД и классе
        if ($this->currentResponsibleType != 'type_3') {

            // Метод обновления типа ответственных
            list(
                'class'  => $updateResponsibleTypeClass,
                'method' => $updateResponsibleTypeMethod
                ) = self::$XMLReader->updateResponsibleType($this->currentDocumentType);

            $transaction->add($updateResponsibleTypeClass, $updateResponsibleTypeMethod, [$this->documentId, 'type_3']);

            $this->currentResponsibleType = 'type_3';
        }

        return $transaction->start()->getLastResults()[$createResponsibleClass][$createResponsibleMethod];
    }


    /**
     * Предназначен для создания новых ответственных "Ответственные пользователи"
     *
     * <b>*</b> Перед использованием метода не требуется вручную удалять текущих ответственных
     *
     * @param int[] индексный массив id пользователей, которых необходимо сделать ответственными
     * @return array
     * @throws DataBaseEx
     * @throws ReflectionException
     * @throws SelfEx
     * @throws TransactionEx
     */
    public function createNewResponsibleType4(array $usersId): array
    {
        $transaction = new Transaction();

        // Добавляем удаление текущих ответственных в траназакцию
        $this->addDeletionCurrentResponsibleToTransaction($transaction);

        // Метод создания ответственных
        list(
            'class'  => $createResponsibleClass,
            'method' => $createResponsibleMethod
            ) = self::$XMLReader->createResponsible($this->currentDocumentType, 'type_4');

        foreach ($usersId as $id) $transaction->add($createResponsibleClass, $createResponsibleMethod, [$this->documentId, $id]);

        // Обновляем текущий тип ответственных у документа в БД и классе
        if ($this->currentResponsibleType != 'type_4') {

            // Метод обновления типа ответственных
            list(
                'class'  => $updateResponsibleTypeClass,
                'method' => $updateResponsibleTypeMethod
                ) = self::$XMLReader->updateResponsibleType($this->currentDocumentType);

            $transaction->add($updateResponsibleTypeClass, $updateResponsibleTypeMethod, [$this->documentId, 'type_4']);

            $this->currentResponsibleType = 'type_4';
        }

        return $transaction->start()->getLastResults()[$createResponsibleClass][$createResponsibleMethod];
    }


    /**
     * Предназначен для проверки пользователя на принадлежность к ответственным
     *
     * @param int $userId id пользователя
     * @return bool <b>true</b> пользователь является ответственным<br>
     * <b>false</b> в противном случае
     * @throws SelfEx
     */
    public function isUserResponsible(int $userId): bool
    {
        list('type' => $type, 'users' => $users) = $this->getCurrentResponsible();

        if ($type == 'type_1' || is_null($users)) {
            return false;
        }

        foreach ($users as $user) if ($user['user_id'] == $userId) return true;
        return false;
    }
}
