<?php


namespace Classes\Section\AccessToDocument;

use Lib\Exceptions\AccessToDocument as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;

use Lib\AccessToDocument\AccessToDocument;
use Tables\DocumentationTypeTableLocator;


/**
 * Предназначен для проверки доступа пользователя к разделу
 *
 */
class AccessToSection extends AccessToDocument
{

    private string $sectionTable;


    /**
     * Конструктор класса
     *
     * @param int $documentId
     * @param int $typeOfObjectId id вида объекта
     * @throws TablesEx
     */
    public function __construct(int $documentId, int $typeOfObjectId)
    {
        parent::__construct($documentId);

        $locator = new DocumentationTypeTableLocator($typeOfObjectId);
        $this->sectionTable = $locator->getDocsSection();
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws SelfEx
     * @throws DataBaseEx
     */
    public function checkAccess(): void
    {

        // TODO: Implement checkAccess() method.
        if (false) {
            throw new SelfEx('', 0);
        }
    }
}