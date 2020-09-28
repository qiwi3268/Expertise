<?php


namespace Classes\Section\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;

use Lib\Singles\VariableTransfer;
use Classes\DocumentTreeHandler;
use Tables\Docs\Relations\ParentDocumentLinker;
use Tables\Docs\application;
use Lib\Actions\Actions as MainActions;
use Tables\DocumentationTypeTableLocator;


/**
 * Предназначен для работы с действиями для типа документа <i>Раздел</i>
 *
 * БД предусматривает две отдельные таблицы для действий (documentation 1 / 2), это сделано на будущее,
 * если понадобится разная бизнесс-логика. В коде же созданы только одни классы без привязки к виду объекта.
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

        // hierarchyTree может быть определен ранее в AccessToDocumentTreeChecker
        if (!is_null($tree = VariableTransfer::getInstance()->getValue('hierarchyTree%S'))) {

            $treeHandler = new DocumentTreeHandler($tree);

            // На случай, если класс будет вызываться не из заявления со сводным замечанием / заключением
            if ($treeHandler->ce_totalCC()) {

                $this->typeOfObjectId = $treeHandler->getTypeOfObjectId();
                $this->defineClasses();
                return;
            }
        }

        // Получаем id заявления напрямую, если до вызова этого класса не было вызова AccessToDocumentTreeChecker
        // (это крайне маловероятно, сделано просто на вырост)
        $linker = new ParentDocumentLinker(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);

        try {

            $applicationId = $linker->getApplicationId();
        } catch (TablesEx $e) {

            if ($e->getCode() == 2001) {

                $applicationId = CURRENT_DOCUMENT_ID;
            } else {

                throw new TablesEx($e->getMessage(), $e->getCode());
            }
        }

        $this->typeOfObjectId = application::getIdTypeOfObjectById($applicationId);
        $this->defineClasses();
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws TablesEx
     */
    protected function defineClasses(): void
    {
        $locator = new DocumentationTypeTableLocator($this->typeOfObjectId);

        $this->actionTable = $locator->getActionsSection();
        $this->accessClass = '\Classes\Section\Actions\AccessActions';
        $this->executionClass = '\Classes\Section\Actions\ExecutionActions';
    }
}