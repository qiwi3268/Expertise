<?php


namespace core\Classes\ControllersInterface;


/**
 * Предоставляет индерфейс для промежуточных классов контроллеров
 *
 */
abstract class Controller
{

    /**
     * Конструктор класса без входных параметров
     *
     */
    public function __construct()
    {
    }


    /**
     * Прослойка перед вызовом основного метода выполнения контроллера
     *
     * Предназначен для однократного вызова только из RoutesXMLHandler.
     * <br>
     * Предназначен для реализации в промежуточном контроллере.
     * <br>
     * Должен поочередно вызвать методы:
     * {@see \core\Classes\ControllersInterface\Controller::construct()}
     * и
     * {@see \core\Classes\ControllersInterface\Controller::doExecute()}
     *
     */
    abstract public function execute(): void;


    /**
     * Основной метод выполнения контроллера
     *
     */
    abstract public function doExecute(): void;
}