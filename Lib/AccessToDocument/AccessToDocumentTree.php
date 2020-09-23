<?php


namespace Lib\AccessToDocument;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\AccessToDocument as SelfEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;
use Exception;

use Tables\Docs\Relations\HierarchyTree;
use Lib\Singles\VariableTransfer;


/**
 * Предназначен для проверки доступа пользователя к документу
 * с учетом проверки доступа <b>ко всему дереву наследования</b> до нужного документа
 *
 */
class AccessToDocumentTree
{

    /**
     * Массив иерархии документов
     *
     */
    private array $tree;

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
     * Предназначен для предва
     *
     * Устанавливает массив иерархии документов в VariableTransfer
     * в ключ <i>hierarchyTree</i>, если его там нет
     *
     * @param string $documentType тип документа
     * @param int $documentId id документа
     * @throws DataBaseEx
     * @throws TablesEx
     * @throws SelfEx
     * @throws Exception
     */
    public function __construct(string $documentType, int $documentId)
    {
        $VT = VariableTransfer::getInstance();

        if (is_null($tree = $VT->getValue('hierarchyTree%S'))) {

            $hierarchyTree = new HierarchyTree($documentType, $documentId);

            $tree = $hierarchyTree->getTree();

            $VT->setValue('hierarchyTree', $tree);
        }

        if (!method_exists($this, $documentType)) {
            throw new SelfEx("В классе Lib\AccessToDocument\AccessToDocumentTree не реализован метод типа документа: '{$documentType}'", 2);
        }

        $this->tree = $tree;
        $this->documentId = $documentId;
        $this->callbacks = call_user_func([$this, $documentType]);
        $this->factory = new Factory();
    }


    /**
     * Предназначен для поочередного вызова метода проверки доступа к каждому документу,
     * определенному в массиве $callbacks
     *
     * @throws DataBaseEx
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function checkAccessToDocumentTree(): void
    {
        foreach ($this->callbacks as $documentType => $params) {

            $this->factory->getObject($documentType, $params)->checkAccess();
        }
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
    // -----------------------------------------------------------------------------------------


    private function application(): array
    {
        return [
            DOCUMENT_TYPE['application'] => [
                $this->documentId,
                -1
            ]
        ];
    }


        private function total_cc(): array
        {
            return [
                DOCUMENT_TYPE['application'] => [
                    $this->tree['id'],
                    $this->documentId
                ],
                DOCUMENT_TYPE['total_cc'] => [
                    $this->documentId
                ]
            ];
        }


            private function section_documentation_1(): array
            {
                $totalCCId = $this->tree['children']['total_cc']['id'];

                return [
                    DOCUMENT_TYPE['application'] => [
                        $this->tree['id'],
                        $totalCCId
                    ],
                    DOCUMENT_TYPE['total_cc'] => [
                        $totalCCId
                    ],
                    DOCUMENT_TYPE['section_documentation_1'] => [
                        $this->documentId
                    ]
                ];
            }
}