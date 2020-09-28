<?php


namespace Classes\TotalCC\Actions;

use Lib\Actions\Actions as MainActions;


/**
 *  Предназначен для работы с действиями для типа документа <i>Сводное замечание / заключение</i>
 *
 */
class Actions extends MainActions
{

    /**
     * Конструктор класса
     *
     */
    public function __construct()
    {
        $this->defineClasses();
    }


    /**
     * Реализация абстрактного метода
     *
     */
    protected function defineClasses(): void
    {
        $this->actionTable = '\Tables\Actions\total_cc';
        $this->accessClass = '\Classes\TotalCC\Actions\AccessActions';
        $this->executionClass = '\Classes\TotalCC\Actions\ExecutionActions';
    }
}