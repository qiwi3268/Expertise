<?php


namespace Lib\Responsible;

use Lib\Exceptions\Responsible as SelfEx;
use Lib\Singles\Helpers\PageAddress as PageAddressHelper;


// Предназначен для работы с ответственными
//
class Responsible
{

    // Виды групп доступа заявлителей к документу (Заявление / ...)
    // Ключ   - название группы доступа заявителя к заявлению
    // Значен - id группы доступа из БД
    private const APPLICANT_ACCESS_GROUP = [
        'full_access'                 => 1,
        'signing_financial_documents' => 2,
        'work_with_comments'          => 3,
        'only_view'                   => 4
    ];

    static private XMLReader $XMLReader;

    // Кеширование ответственных
    static private array $cacheResponsible = [
        'application' => [
            'type_2' => null,
            'type_3' => null,
            'type_4' => null
        ]
    ];

    private string $currentResponsibleType; // Текущий тип ответственных
    private int $documentId;                // id документа
    private string $currentDocumentType;    // Текущий тип документа


    // Принимает параметры------------------------------------
    // documentId       int : id документа
    // documentType ?string : тип документа, согласно константе DOCUMENT_TYPE. Если тип документа не
    //                        передан, то определяется тип по умолчанию для открытого документа
    // Выбрасывает исключения---------------------------------
    // Classes\Exceptions\Responsible :
    // code:
    //  1  - передан некорректный тип документа
    //  2  - методу Lib\Singles\Helpers\PageAddress::getDocumentType не удалось определить тип документа
    //
    public function __construct(int $documentId, ?string $documentType = null)
    {
        if (!is_null($documentType)) {

            if (!isset(DOCUMENT_TYPE[$documentType])) {
                throw new SelfEx("Передан некорректный тип документа: '{$documentType}'", 1);
            }
        } else {

            if (is_null($documentType = PageAddressHelper::getDocumentType())) {
                throw new SelfEx('Методу Lib\Singles\Helpers\PageAddress::getDocumentType не удалось определить тип документа', 2);
            }
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


    // Предназначен для получения текущих ответственных
    // Возвращает параметры------------------------------------
    // array : массив формата:
    //    'type'  => тип ответственных
    //    'users' => ?array : null / ассоциативные массивы ответственных пользователей
    //
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
    

    // Предназначен для удаления текущих ответственных
    // Принимает параметры------------------------------------
    // needUpdateResponsibleType bool : нужно ли обновлять тип ответственных в текущем документе на type_1
    // Возвращает параметры-----------------------------------
    // array : массив формата:
    //    'type'  => тип ответственных
    //    'users' => ?array : null / ассоциативные массивы ответственных пользователей
    //
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

    
    // Предназначен для создания новых ответственных "Ответственные группы заявителей"
    // * Перед использованием метода не требуется вручную удалять текущих ответственных
    // Принимает параметры------------------------------------
    // accessGroupNames string : перечисление названий групп заявителей (согласно константе APPLICANT_ACCESS_GROUP)
    // Возвращает параметры-----------------------------------
    // array : индексный массив id созданных записей ответственных групп заявителей
    // Выбрасывает исключения---------------------------------
    // Classes\Exceptions\Responsible :
    // code:
    //  3  - не существует указанного названия группы доступа заявителя к заявлению
    //
    public function createNewResponsibleType3(string ...$accessGroupNames): array
    {
        // Удаляем текущие ответственные группы заявителей
        $this->deleteCurrentResponsible(false);

        $accessGroupIds = [];

        // Получение id групп ответственных из принятых названий
        foreach ($accessGroupNames as $accessGroupName) {

            if (!isset(self::APPLICANT_ACCESS_GROUP[$accessGroupName])) {
                throw new SelfEx("Не существует указанного названия группы доступа заявителя к заявлению: '{$accessGroupName}'", 3);
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


    // Предназначен для проверки пользователя на принадлежность к ответственным
    // Принимает параметры------------------------------------
    // userId int : id пользователя
    // Возвращает параметры-----------------------------------
    // bool : true  - пользователь является ответственным
    //        false - в противном случае
    //
    public function isUserResponsible(int $userId): bool
    {
        list('type' => $type, 'users' => $users) = $this->getCurrentResponsible();

        switch ($type) {
            case 'type_1':
                return false;
            case 'type_2':
                //todo
            case 'type_3':

                if (is_null($users)) {

                    return false;
                } else {

                    foreach ($users as $user) if ($user['user_id'] == $userId) return true;
                    return false;
                }
            case 'type_4':
                //todo
        }
    }
}
