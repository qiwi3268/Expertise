<?php


namespace ControllersClasses;
use Lib\Singles\VariableTransfer;


/**
 * Предоставляет индерфейс для классов контроллеров
 *
 */
abstract class Controller
{

    protected VariableTransfer $VT;


    /**
     * Конструктор класса без входых параметров
     *
     */
    public function __construct()
    {
        $this->VT = VariableTransfer::getInstance();
    }


    /**
     * Основной метод выполнения контроллера
     *
     */
    abstract public function execute(): void;
}