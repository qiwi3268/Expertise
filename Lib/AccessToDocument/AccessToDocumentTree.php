<?php


namespace Lib\AccessToDocument;

use Tables\Docs\Relations\HierarchyTree;
use Lib\AccessToDocument\Factory;
use Lib\Singles\VariableTransfer;


/**
 * Предназначен для проверки доступа пользователя к документу
 * с учетом проверки доступа ко всему дереву наследования
 *
 */
class AccessToDocumentTree
{

    /**
     * Ассоциативный массив формата:
     *
     * Ключ - название типа документа<br>
     * Значение - индексный массив с элементами - названиями типов документов в том порядке,
     * в котором они должны быть проверены от родительского документа к дочернему
     *
     */
    private const MAPPING = [

        DOCUMENT_TYPE['application'] => [
            DOCUMENT_TYPE['application']
        ],

        DOCUMENT_TYPE['total_cc'] => [
            DOCUMENT_TYPE['application'],
            DOCUMENT_TYPE['total_cc']
        ],

        DOCUMENT_TYPE['section_documentation_1'] => [
            DOCUMENT_TYPE['application'],
            DOCUMENT_TYPE['total_cc'],
            DOCUMENT_TYPE['section_documentation_1']
        ]
    ];


    private array $line;

    private string $documentType;

    private int $documentId;


    /**
     * Конструктор класса
     *
     * Предназначен для определения линии проверок
     *
     * @param string $documentType
     */
    public function __construct(string $documentType, int $documentId)
    {
        if (!isset(self::MAPPING[$documentType])) {
            //todo throw
        }

        $this->line = self::MAPPING[$documentType];
        $this->documentType = $documentType;
        $this->documentId = $documentId;
    }

    public function checkAccessToDocumentTree(): void
    {
        $VT = VariableTransfer::getInstance();

        if (is_null($tree = $VT->getValue('hierarchyTree%S'))) {

            $hierarchyTree = new HierarchyTree($this->documentType, $this->documentId);

            $tree = $hierarchyTree->getTree();

            $VT->setValue('hierarchyTree', $tree);
        }

        $factory = new Factory();

        $params = [
            DOCUMENT_TYPE['application'] => [
                $tree['id']
            ],
            DOCUMENT_TYPE['total_cc'] => [
                $tree['children']['total_cc']['id']
            ]
        ];


        var_dump($tree);

        foreach ($this->line as $documentType) {

            var_dump($documentType);
        }


    }


}