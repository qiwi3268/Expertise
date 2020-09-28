<?php


namespace Classes\Application\Actions;

use Lib\Actions\Actions as MainActions;


/**
 *  Предназначен для работы с действиями для типа документа <i>Заявление</i>
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
        $this->actionTable = '\Tables\Actions\application';
        $this->accessClass = '\Classes\Application\Actions\AccessActions';
        $this->executionClass = '\Classes\Application\Actions\ExecutionActions';
    }
}