<?php


namespace Tables\Docs\Relations;

use Tables\Exceptions\Tables as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Предназначен для получения данных о родительском докумнте от текущего документа
 *
 */
class ParentDocumentLinker
{

    /**
     * Тип документа
     *
     */
    private string $documentType;

    /**
     * id документа
     *
     */
    private int $documentId;


    /**
     * Конструктор класса
     *
     * @param string $documentType тип документа
     * @param int $documentId id документа
     */
    public function __construct(string $documentType, int $documentId)
    {
        $this->documentType = $documentType;
        $this->documentId = $documentId;
    }


    /**
     * Предназначен для получения id документа, принятого в конструкторе
     *
     * @return int
     */
    public function getDocumentId(): int
    {
        return $this->documentId;
    }


    /**
     * Предназначен для получения id заявления от переданного документа
     *
     * Метод возвращает исключение, если вызван от документа типа "Заявление"
     *
     * @return int id заявления
     * @throws SelfEx
     * @throws DataBaseEx
     */
    public function getApplicationId(): int
    {
        if ($this->documentType == DOCUMENT_TYPE['application']) {
            throw new SelfEx("Метод Tables\Docs\Relations\ParentDocumentLinker::getApplicationId невозможно вызвать от типа документа 'Заявление'", 2001);
        }

        switch ($this->documentType) {

            case DOCUMENT_TYPE['total_cc'] :
                return total_cc::getIdMainDocumentById($this->documentId);

            case DOCUMENT_TYPE['section_documentation_1'] :
                $totalCCId = section_documentation_1::getIdMainDocumentById($this->documentId);
                return total_cc::getIdMainDocumentById($totalCCId);

            default :
                throw new SelfEx("Методу Tables\Docs\Relations\ParentDocumentLinker::getApplicationId не удалось определить тип документа: '{$this->documentType}'", 2002);
        }
    }

}