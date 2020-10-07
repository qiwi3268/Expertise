<?php


namespace Tables\Docs\Relations;

use Tables\Exceptions\Tables as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Класс-обертка для работы с {@see \Tables\Docs\Relations\ParentDocumentLinker}
 *
 * Паттерн: <i>Facade</i>
 */
class ParentDocumentLinkerFacade
{

    /**
     * Экземпляр оборачиваемого класса
     *
     */
    private ParentDocumentLinker $parentDocumentLinker;


    /**
     * Конструктор класса
     *
     * @param string $documentType тип документа
     * @param int $documentId id документа
     */
    public function __construct(string $documentType, int $documentId)
    {
        $this->parentDocumentLinker = new ParentDocumentLinker($documentType, $documentId);
    }


    /**
     * Обертка над исходным методом получения id заявления
     *
     * В отличие от метода главного класса, не выбрасывает исключение,
     * если переданный документ является заявлением
     *
     * @uses \Tables\Docs\Relations\ParentDocumentLinker::getApplicationId()
     * @return int
     * @throws SelfEx
     * @throws DataBaseEx
     */
    public function getApplicationId(): int
    {

        try {

            return $this->parentDocumentLinker->getApplicationId();
        } catch (SelfEx $e) {

            if ($e->getCode() == 2001) {

                return $this->parentDocumentLinker->getDocumentId();
            } else {

                throw new SelfEx($e->getMessage(), $e->getCode());
            }
        }
    }

}