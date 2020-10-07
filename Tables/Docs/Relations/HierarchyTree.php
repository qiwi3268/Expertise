<?php


namespace Tables\Docs\Relations;

use Tables\Exceptions\Tables as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;



/**
 * Предназначен для получения дерева иерархии документов от любого документа
 *
 */
class HierarchyTree
{

    /**
     * id заявления
     *
     */
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
        $facade = new ParentDocumentLinkerFacade($documentType, $documentId);
        $this->applicationId = $facade->getApplicationId();
    }


    /**
     * Предназначен для получения массива иерархии документов
     *
     * Использует метод получения дочерних документов от заявления
     *
     * @uses \Tables\Docs\Relations\application::getChildrenById()
     * @return array
     * @throws DataBaseEx
     * @throws SelfEx
     */
    public function getTree(): array
    {
        return application::getChildrenById($this->applicationId);
    }
}