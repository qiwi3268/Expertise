<?php


namespace Lib\AccessToDocument;

use Lib\Exceptions\AccessToDocument as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use Lib\Singles\DocumentTreeHandler;



/**
 * Предназначен для проверки доступа пользователя к документу
 * с учетом проверки доступа <b>ко всему дереву наследования</b> до нужного документа
 *
 */
class AccessToDocumentTree
{

    /**
     * Статический индексный массив проверенных документов формата:<br>
     * ['doc' => (string)'application', (int)'id' => 7], [...], ...
     *
     */
    static private array $checkedDocuments = [];


    /**
     * Обработчик массива иерархии документов
     *
     */
    private DocumentTreeHandler $treeHandler;


    /**
     * id документа, для которого определяется доступ
     *
     */
    private int $documentId;

    /**
     * Ассоциативный массив callback'ов для типа документа
     *
     */
    private array $callbacks;

    /**
     * Фабрика для получения экземпляров классов проверки доступа
     *
     */
    private Factory $factory;


    /**
     * Конструктор класса
     *
     * Устанавливает массив иерархии документов в VariableTransfer
     * в ключ <i>hierarchyTree</i>, если его там нет
     *
     * @param string $documentType тип документа
     * @param int $documentId id документа
     * @throws DataBaseEx
     * @throws TablesEx
     * @throws SelfEx
     * @throws DocumentTreeHandlerEx
     */
    public function __construct(string $documentType, int $documentId)
    {
        if (!method_exists($this, $documentType)) {
            throw new SelfEx("В классе Lib\AccessToDocument\AccessToDocumentTree не реализован метод типа документа: '{$documentType}'", 2001);
        }

        $this->treeHandler = DocumentTreeHandler::setInstanceByKey('AccessToDocumentTree', $documentType, $documentId);
        $this->documentId = $documentId;
        $this->callbacks = call_user_func([$this, $documentType]);
        $this->factory = new Factory();
    }


    /**
     * Предназначен для поочередного вызова метода проверки доступа к каждому документу,
     * определенному в массиве $callbacks
     *
     * Записывает в статический массив данные о проверенных документах<br>
     * <b>*</b> id документа идет как первый элемент из массива $params
     *
     * @throws DataBaseEx
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function checkAccessToDocumentTree(): void
    {
        foreach ($this->callbacks as $documentType => $params) {

            $this->factory->getObject($documentType, $params)->checkAccess();

            self::$checkedDocuments[] = [
                'doc' => $documentType,
                'id'  => (int)$params[0]
            ];
        }
    }


    /**
     * Предназначен для получения массива проверенных документов
     *
     * @return array
     */
    static public function getCheckedDocuments(): array
    {
        return self::$checkedDocuments;
    }


    // -----------------------------------------------------------------------------------------
    // Блок методов для проверки доступа пользователя к документу с учетом дерева наследования
    // до нужного документа. В этом блоке необходимо реализовать методы с названиями для
    // каждого типа документа, в которых будут возвращены массивы определенного формата.
    //
    // Реализация закрытых методов для получения ассоциативного массива формата:
    //    Ключ     - название типа документа
    //    Значение - индексный массив с параметрами, которые будут переданы
    //               в класс проверки доступа пользователя к документу
    //
    // Порядок в массиве первого уровня важен. В указанном порядке будут созданы объекты
    // соответствующих классов и вызваны методы проверки checkAccess.
    //
    // При работе с классом DocumentTreeHandler опускаются проверки на существование документов,
    // т.к. существование документа было проверено предшествующем route callback DocumentExistChecker
    // -----------------------------------------------------------------------------------------


    private function application(): array
    {
        if ($this->treeHandler->ce_totalCC()) {
            $totalCCid = $this->treeHandler->getTotalCCId();
        } else {
            $totalCCid = null;
        }

        return [
            DOCUMENT_TYPE['application'] => [
                $this->documentId,
                $totalCCid
            ]
        ];
    }


    private function total_cc(): array
    {
        return [
            DOCUMENT_TYPE['application'] => [
                $this->treeHandler->getApplicationId(),
                $this->documentId
            ],
            DOCUMENT_TYPE['total_cc'] => [
                $this->documentId
            ]
        ];
    }


    private function section_documentation_1(): array
    {
        return [
            DOCUMENT_TYPE['application'] => [
                $this->treeHandler->getApplicationId(),
                $this->treeHandler->getTotalCCId()
            ],
            DOCUMENT_TYPE['total_cc'] => [
                $this->treeHandler->getTotalCCId()
            ],
            DOCUMENT_TYPE['section_documentation_1'] => [
                $this->documentId,
                $this->treeHandler->getTypeOfObjectId()
            ]
        ];
    }


    private function section_documentation_2(): array
    {
        return [
            DOCUMENT_TYPE['application'] => [
                $this->treeHandler->getApplicationId(),
                $this->treeHandler->getTotalCCId()
            ],
            DOCUMENT_TYPE['total_cc'] => [
                $this->treeHandler->getTotalCCId()
            ],
            DOCUMENT_TYPE['section_documentation_2'] => [
                $this->documentId,
                $this->treeHandler->getTypeOfObjectId()
            ]
        ];
    }

}