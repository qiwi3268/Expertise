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
     * Тип текущего документа
     *
     */
    private string $DT;


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
        $this->DT = $documentType;
    }


    /**
     * Предназначен для получения текущих ответственных
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

            // Метод получения ответственных
            list(
                'class'  => $getResponsibleClass,
                'method' => $getResponsibleMethod
                ) = self::$XMLReader->getResponsible($this->DT, $this->currentResponsibleType);

            $responsible = $getResponsibleClass::$getResponsibleMethod($this->documentId);
        }

        return [
            'type'  => $this->currentResponsibleType,
            'users' => $responsible
        ];
    }


    /**
     * Предназначен для удаления текущих ответственных
     *
     * @param Transaction $transaction транзакция, в которую будут записаны результаты метода
     * @param bool $needUpdateResponsibleType нужно ли обновлять тип ответственных в текущем документе на type_1
     * @throws ReflectionException
     * @throws SelfEx
     * @throws TransactionEx
     */
    public function deleteCurrentResponsible(Transaction $transaction, bool $needUpdateResponsibleType = true): void
    {
        if ($this->currentResponsibleType == 'type_1') {
            return;
        }

        // Метод удаления ответственных
        list(
            'class'  => $deleteResponsibleClass,
            'method' => $deleteResponsibleMethod
            ) = self::$XMLReader->deleteResponsible($this->DT, $this->currentResponsibleType);

        $transaction->add($deleteResponsibleClass, $deleteResponsibleMethod, [$this->documentId]);


        // Устанавливаем в главном документе в БД тип ответственных "Никто"
        if ($needUpdateResponsibleType) {

            $this->updateCurrentResponsibleType($transaction, 'type_1');
        }
        $this->currentResponsibleType = 'type_1';
    }


    /**
     * Предназначен для создания ответственных "Ответственные группы заявителей"
     *
     * <b>****</b> Перед использованием метода требуется вручную удалить текущих ответственных
     *
     * @param Transaction $transaction транзакция, в которую будут записаны результаты метода
     * @param string[] $accessGroupNames индексный массив названий групп ответственных заявителей
     * @param bool $needUpdateCurrentResponsibleType требуется ли обновлять `responsible_type` у документа в БД
     * @return void
     * @throws ReflectionException
     * @throws SelfEx
     * @throws TransactionEx
     */
    public function createResponsibleType3(Transaction $transaction, array $accessGroupNames, bool $needUpdateCurrentResponsibleType = true): void
    {
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
            ) = self::$XMLReader->createResponsible($this->DT, 'type_3');

        foreach ($accessGroupIds as $id) {

            $transaction->add($createResponsibleClass, $createResponsibleMethod, [$this->documentId, $id]);
        }

        if ($needUpdateCurrentResponsibleType) {

            $this->updateCurrentResponsibleType($transaction, 'type_3');
        }
        $this->currentResponsibleType = 'type_3';
    }


    /**
     * Предназначен для создания ответственных "Ответственные пользователи"
     *
     * <b>****</b> Перед использованием метода требуется вручную удалить текущих ответственных
     *
     * @param Transaction $transaction транзакция, в которую будут записаны результаты метода
     * @param int[] индексный массив id пользователей, которых необходимо сделать ответственными
     * @param bool $needUpdateCurrentResponsibleType требуется ли обновлять `responsible_type` у документа в БД
     * @return void
     * @throws ReflectionException
     * @throws SelfEx
     * @throws TransactionEx
     */
    public function createResponsibleType4(Transaction $transaction, array $usersId, bool $needUpdateCurrentResponsibleType = true): void
    {
        // Метод создания ответственных
        list(
            'class'  => $createResponsibleClass,
            'method' => $createResponsibleMethod
            ) = self::$XMLReader->createResponsible($this->DT, 'type_4');

        foreach ($usersId as $id) {

            $transaction->add($createResponsibleClass, $createResponsibleMethod, [$this->documentId, $id]);
        }

        if ($needUpdateCurrentResponsibleType) {

            $this->updateCurrentResponsibleType($transaction, 'type_4');
        }
        $this->currentResponsibleType = 'type_4';
    }


    /**
     * Предназначен для обновления поля `responsible_type` в записи документа
     *
     * Проверка на тип ответственных опускается, т.к. данный метод недоступен клиентскому коду
     *
     * @param Transaction $transaction транзакция, в которую будут записаны результаты метода
     * @param string $newType новый тип ответственных
     * @throws ReflectionException
     * @throws SelfEx
     * @throws TransactionEx
     */
    private function updateCurrentResponsibleType(Transaction $transaction, string $newType): void
    {
        // Метод обновления типа ответственных
        list(
            'class'  => $class,
            'method' => $method
            ) = self::$XMLReader->updateResponsibleType($this->DT);

        $transaction->add($class, $method, [$this->documentId, $newType]);
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
