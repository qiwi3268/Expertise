<?php


namespace core\Classes\ControllersInterface;
use Lib\Singles\VariableTransfer;


/**
 * Предоставляет индерфейс для классов Page-контроллеров
 *
 */
abstract class PageController extends Controller
{

    protected VariableTransfer $VT;


    /**
     * Конструктор класса без входных параметров
     *
     */
    final public function __construct()
    {
        parent::__construct();
        $this->VT = VariableTransfer::getInstance();
    }


    /**
     * Реализация абстрактного метода
     *
     */
    public function execute(): void
    {
        $this->construct();
        $this->doExecute();
    }
}