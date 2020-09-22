<?php


namespace Lib\AccessToDocument;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use Tables\Docs\Relations\HierarchyTree;
use Lib\Singles\VariableTransfer;


/**
 * Предназначен для проверки доступа пользователя к документу
 * с учетом проверки доступа ко всему дереву наследования до нужного документа
 *
 */
class AccessToDocumentTree
{

    private array $tree;
    private string $documentType;
    private int $documentId;
    private Factory $factory;



    /**
     * Конструктор класса
     *
     * Предназначен для определения линии проверок
     *
     * @param string $documentType
     * @param int $documentId
     * @throws DataBaseEx
     * @throws TablesEx
     */
    public function __construct(string $documentType, int $documentId)
    {
        $VT = VariableTransfer::getInstance();

        if (is_null($tree = $VT->getValue('hierarchyTree%S'))) {

            $hierarchyTree = new HierarchyTree($documentType, $documentId);

            $tree = $hierarchyTree->getTree();

            $VT->setValue('hierarchyTree', $tree);
        }

        $this->tree = $tree;
        $this->documentType = $documentType;
        $this->documentId = $documentId;
        $this->factory = new Factory();
    }


    /**
     * @throws DataBaseEx
     * @throws AccessToDocumentEx
     * @throws ReflectionException
     */
    public function checkAccessToDocumentTree(): void
    {
        foreach (call_user_func([$this, $this->documentType]) as $documentType => $params) {

            $this->factory->getObject($documentType, $params)->checkAccess();
        }
    }


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