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
     * Конструктор класса без входных параметров
     *
     * Конструктор нельзя переопределеять, поскольку на уровне RoutesXMLHandler сначала создаются
     * экземпляры всех объектов, а только потом происходит поочередный вызов методов.
     * Таким образом, наследуемый класс может попытаться воспользоваться тем объектами / константами,
     * работа с которыми еще не была осуществлена
     *
     */
    final public function __construct()
    {
        $this->VT = VariableTransfer::getInstance();
    }


    /**
     * Замена магическому констркутору
     *
     * Будет вызван перед методом {@see \ControllersClasses\Controller::doExecute()}
     */
    protected function construct(): void
    {

    }


    /**
     * Прослойка перед вызовом основного метода выполнения контроллера
     *
     */
    public function execute(): void
    {
        $this->construct();
        $this->doExecute();
    }


    /**
     * Основной метод выполнения контроллера
     *
     */
    abstract public function doExecute(): void;
}