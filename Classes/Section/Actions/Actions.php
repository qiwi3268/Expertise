<?php


namespace Classes\Section\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Tables\Exceptions\Tables as TablesEx;

use Lib\Singles\DocumentTreeHandler;
use Lib\Actions\Actions as MainActions;
use Tables\Locators\TypeOfObjectTableLocator;
use Tables\Locators\DocumentTypeTableLocator;


/**
 * Предназначен для работы с действиями для типа документа <i>Раздел</i>
 *
 * БД предусматривает две отдельные таблицы для действий (documentation 1 / 2), это сделано на будущее,
 * если понадобится разная бизнесс-логика. В коде же (в пакете Classes\Section\Actions)
 * созданы только одни классы без привязки к виду объекта.
 * Если действия из таблиц начнут отличаться, то следует просто давать им разные названия callback'ов,
 * а реализовывать их по прежнему в одних классах
 *
 */
class Actions extends MainActions
{

    /**
     * id вида объекта
     *
     */
    private int $typeOfObjectId;

    public DocumentTreeHandler $documentTreeHandler;
    public TypeOfObjectTableLocator $typeOfObjectTableLocator;
    public DocumentTypeTableLocator $documentTypeTableLocator;


    /**
     * Конструктор класса
     *
     * Метод самостоятельно определяет с таблицей какого вида разделов будет
     * производиться работа
     *
     * @throws TablesEx
     * @throws DocumentTreeHandlerEx
     * @throws DataBaseEx
     */
    public function __construct()
    {
        try {

            $typeOfObjectId = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree')->getTypeOfObjectId();
        } catch (DocumentTreeHandlerEx $e) {

            // Экземпляр класса не существует в хранилище
            if ($e->getCode() == 2) {
                $typeOfObjectId = DocumentTreeHandler::setInstanceByKey('SectionActions', CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID)->getTypeOfObjectId();
            } else {
                throw new DocumentTreeHandlerEx($e->getMessage(), $e->getCode());
            }
        }



        $this->typeOfObjectId = $typeOfObjectId;

        // Вспомогательные объекты
        $this->typeOfObjectTableLocator = new TypeOfObjectTableLocator($typeOfObjectId);
        $this->documentTypeTableLocator = new DocumentTypeTableLocator(CURRENT_DOCUMENT_TYPE);

        $this->defineClasses();
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws TablesEx
     */
    protected function defineClasses(): void
    {
        $locator = new TypeOfObjectTableLocator($this->typeOfObjectId);

        $this->actionTable = $locator->getActionsSection();
        $this->accessClass = '\Classes\Section\Actions\AccessActions';
        $this->executionClass = '\Classes\Section\Actions\ExecutionActions';
    }
}