<?php


namespace core\Classes;

use core\Classes\Exceptions\XMLHandler as RouteHandlerEx;
use core\Classes\XMLHandler as RouteHandler;
use SimpleXMLElement;


final class Route
{

    private RouteHandler $routeHandler;
    private SimpleXMLElement $page;

    private bool $routeExist;


    // Принимает параметры-----------------------------------
    // requestURI string : URI входящего на сервер запроса
    //
    public function __construct(string $requestURI)
    {
        // Полный запрос с первым '/' и get-параметрами
        define('URI', $requestURI);
        // Запрос в формате без первого '/' и get-параметров
        define('URN', mb_substr(parse_url($requestURI, PHP_URL_PATH), 1));

        $this->routeHandler = new RouteHandler();

        try {

            $this->page = $this->routeHandler->getPage(URN);
            $this->routeExist = true;
        } catch (RouteHandlerEx $e) {

            $e_message = $e->getMessage();
            $e_code = $e->getCode();

            // Не найдено узлов по XML-пути
            if ($e_code == 3) {
                $this->routeExist = false;
            } else {
                throw new RouteHandlerEx($e_message, $e_code);
            }
        }
    }


    public function isRouteExist(): bool
    {
        return $this->routeExist;
    }


    public function validatePageStructure(): self
    {
        $this->routeHandler->validatePageStructure($this->page);
        return $this;
    }


    public function handleValidatedPageValues(): void
    {
        $this->routeHandler->handleValidatedPageValues($this->page);
    }
}