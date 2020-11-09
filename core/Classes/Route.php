<?php


namespace core\Classes;

use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use SimpleXMLElement;


/**
 * Предназначен маршрутизации запроса
 *
 */
final class Route
{

    private RoutesXMLHandler $routesHandler;

    /**
     * XML узел page входящего запроса
     *
     */
    private SimpleXMLElement $page;

    /**
     * Флаг существования страницы по входящему запросу
     *
     */
    private bool $routeExist;


    /**
     * Конструктор класса
     *
     * @throws XMLValidatorEx
     * @throws Exceptions\RoutesXMLHandler
     */
    public function __construct()
    {
        $this->routesHandler = new RoutesXMLHandler();

        try {

            $this->page = $this->routesHandler->getPage(URN);
            $this->routeExist = true;
        } catch (XMLValidatorEx $e) {

            $code = $e->getCode();

            // Не найдено узлов по XML пути
            if ($code == 6) {
                $this->routeExist = false;
            } else {
                throw new XMLValidatorEx($e->getMessage(), $code);
            }
        }
    }


    /**
     * Предназначен для проверки существования маршрута
     *
     * @return bool
     */
    public function isRouteExist(): bool
    {
        return $this->routeExist;
    }


    /**
     * @uses \core\Classes\RoutesXMLHandler::validatePageStructure()
     * @return $this объект класса для построения цепочки вызовов
     * @throws XMLValidatorEx
     */
    public function validatePageStructure(): self
    {
        $this->routesHandler->validatePageStructure($this->page);
        return $this;
    }


    /**
     * @uses \core\Classes\RoutesXMLHandler::handleValidatedPageValues()
     * @throws Exceptions\RoutesXMLHandler
     * @throws XMLValidatorEx
     */
    public function handleValidatedPageValues(): void
    {
        $this->routesHandler->handleValidatedPageValues($this->page);
    }
}