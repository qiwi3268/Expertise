<?php


namespace core\Classes;

use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use core\Classes\RoutesXMLHandler as RoutesXMLHandler;
use SimpleXMLElement;


/**
 * Предназначен маршрутизации запроса
 *
 */
final class Route
{

    private RoutesXMLHandler $routesHandler;

    /**
     * XML-узел page входящего запроса
     *
     */
    private SimpleXMLElement $page;

    /**
     * Флаг существования страницы по входящему запросу
     *
     */
    private bool $routeExist;


    /**
     * Конструктор класса.
     *
     * @param string $requestURI URI входящего на сервер запроса
     * @throws XMLValidatorEx
     */
    public function __construct(string $requestURI)
    {
        // Полный запрос с первым '/' и get-параметрами
        define('URI', $requestURI);
        // Запрос в формате без первого '/' и get-параметров
        define('URN', mb_substr(parse_url($requestURI, PHP_URL_PATH), 1));

        $this->routesHandler = new RoutesXMLHandler();

        try {

            $this->page = $this->routesHandler->getPage(URN);
            $this->routeExist = true;
        } catch (XMLValidatorEx $e) {

            $e_message = $e->getMessage();
            $e_code = $e->getCode();

            // Не найдено узлов по XML-пути
            if ($e_code == 6) {
                $this->routeExist = false;
            } else {
                throw new XMLValidatorEx($e_message, $e_code);
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
     * @uses RoutesXMLHandler::validatePageStructure()
     * @return $this объект класса для построения цепочки вызовов
     * @throws XMLValidatorEx
     */
    public function validatePageStructure(): self
    {
        $this->routesHandler->validatePageStructure($this->page);
        return $this;
    }


    /**
     * @uses RoutesXMLHandler::handleValidatedPageValues()
     * @throws Exceptions\RoutesXMLHandler
     */
    public function handleValidatedPageValues(): void
    {
        $this->routesHandler->handleValidatedPageValues($this->page);
    }
}