<?php


namespace core\Classes\ControllersInterface;

use core\Classes\Request\HttpRequest;
use Lib\Singles\VariableTransfer;


/**
 * Предоставляет индерфейс для классов Page-контроллеров
 *
 */
abstract class PageController extends Controller
{

    /**
     * Объект http запроса на сервер
     *
     */
    protected HttpRequest $request;

    protected VariableTransfer $VT;


    /**
     * Конструктор класса без входных параметров
     *
     */
    public function __construct()
    {
        $this->request = HttpRequest::getInstance();
        $this->VT = VariableTransfer::getInstance();
    }


    /**
     * Реализация абстрактного метода
     *
     * <b>*</b> Предназначен для вызова из контекста RoutesXMLHandler
     *
     */
    public function execute(): void
    {
        $this->doExecute();
    }
}