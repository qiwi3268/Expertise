<?php


namespace Classes\Application\Actions;
use Lib\Actions\Actions as MainActions;
use Lib\Actions\AccessActions as MainAccessActions;
use Lib\Actions\ExecutionActions as MainExecutionActions;
use Tables\Actions\application as ActionTable;


/**
 *  Предназначен для работы с действиями для типа документа <i>Заявление</i>
 *
 */
class Actions extends MainActions
{

    /**
     * Реализация абстрактного метода
     *
     * @return array
     */
    public function getAssocActiveActions(): array
    {
        return ActionTable::getAllActive();
    }

    /**
     * Реализация абстрактного метода
     *
     * @param string $pageName
     * @return array|null
     */
    public function getAssocActiveActionByPageName(string $pageName): ?array
    {
        return ActionTable::getAssocActiveByPageName($pageName);
    }

    /**
     * Реализация абстрактного метода
     *
     * @param string $pageName
     * @return array|null
     */
    public function getAssocActionByPageName(string $pageName): ?array
    {
        return ActionTable::getAssocByPageName($pageName);
    }

    /**
     * Реализация абстрактного метода
     *
     * @return MainAccessActions
     */
    public function getAccessActions(): MainAccessActions
    {
        return new AccessActions($this);
    }

    /**
     * Реализация абстрактного метода
     *
     * @return MainExecutionActions
     */
    public function getExecutionActions(): MainExecutionActions
    {
        return new ExecutionActions($this);
    }
}