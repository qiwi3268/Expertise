<?php


namespace Tables\Docs\Relations;

use Tables\Exceptions\Tables as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Предназначен для получения иерархии документов от любого документа
 *
 */
class HierarchyTree
{

    private int $applicationId;

    /**
     * Конструктор класса
     *
     * Получает id заявления из любого принятого типа документа
     *
     * @param $documentType string тип документа
     * @param $documentId int id документа
     * @throws SelfEx
     * @throws DataBaseEx
     */
    public function __construct(string $documentType, int $documentId)
    {
        if ($documentType == DOCUMENT_TYPE['application']) {

            $this->applicationId = $documentId;
        } elseif ($documentType == DOCUMENT_TYPE['total_cc']) {

            $this->applicationId = total_cc::getIdMainDocumentById($documentId);
        } elseif ($documentType == DOCUMENT_TYPE['section_documentation_1']) {

            $totalCCId = section_documentation_1::getIdMainDocumentById($documentId);
            $this->applicationId = total_cc::getIdMainDocumentById($totalCCId);
        } else {
            throw new SelfEx("Методу Tables\Docs\Relations\HierarchyTree::__construct не удалось определить тип документа: '{$documentType}'", 2);
        }
    }


    /**
     * Предназначен для получения массива иерархии документов
     *
     * Использует метод получения дочерних документов от заявления
     *
     * @uses \Tables\Docs\Relations\application::getChildrenById()
     * @return array
     * @throws DataBaseEx
     */
    public function getTree(): array
    {
        return application::getChildrenById($this->applicationId);
    }
}