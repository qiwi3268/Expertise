<?php


namespace core\Classes\ControllersInterface;


/**
 * Предоставляет индерфейс для классов API-контроллеров
 *
 */
abstract class APIController extends Controller
{

    /**
     * Конструктор класса без входных параметров
     *
     */
    final public function __construct()
    {
        parent::__construct();
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