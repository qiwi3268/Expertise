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
     * На уровне промежуточных контроллеров необходимо делать final констркутор,
     * поскольку в RoutesXMLHandler сначала создаются экземпляры всех объектов,
     * а только потом происходит поочередный вызов методов.
     * Таким образом, наследуемый класс может попытаться воспользоваться тем объектами / константами,
     * работа с которыми еще не была осуществлена
     *
     */
    public function __construct()
    {
    }


    /**
     * Замена магическому констркутору
     *
     * Будет вызван перед методом {@see \core\Classes\ControllersInterface\Controller::doExecute()}
     */
    protected function construct(): void
    {
    }


    /**
     * Прослойка перед вызовом основного метода выполнения контроллера
     *
     * <b>*</b> Предназначен для реализации в промежуточном контроллере.
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