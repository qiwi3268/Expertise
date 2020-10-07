<?php


namespace Classes\Section\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;

use Lib\Singles\VariableTransfer;
use Classes\DocumentTreeHandler;
use Tables\Docs\Relations\ParentDocumentLinkerFacade;
use Tables\Docs\application;
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

    public TypeOfObjectTableLocator $typeOfObjectTableLocator;
    public DocumentTypeTableLocator $documentTypeTableLocator;


    /**
     * Конструктор класса
     *
     * Метод самостоятельно определяет с таблицей какого вида разделов будет
     * производиться работа
     *
     * @throws DataBaseEx
     * @throws TablesEx
     */
    public function __construct()
    {

        // Получение вида объекта (для получения конкретной таблицы с действиями нужного типа)

        // hierarchyTree должен быть определен ранее в AccessToDocumentTreeChecker
        if (
            !is_null($tree = VariableTransfer::getInstance()->getValue('hierarchyTree%S'))
            // На случай, если класс будет вызываться не из заявления со сводным замечанием / заключением
            && (($treeHandler = new DocumentTreeHandler($tree))->ce_totalCC())
        ) {

            $typeOfObjectId = $treeHandler->getTypeOfObjectId();
        } else {

            // Получаем id заявления напрямую, если до вызова этого класса не было вызова AccessToDocumentTreeChecker
            // (это крайне маловероятно, сделано просто на вырост)
            $facade = new ParentDocumentLinkerFacade(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
            $typeOfObjectId = application::getIdTypeOfObjectById($facade->getApplicationId());;
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