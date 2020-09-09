<?php


namespace core\Classes;


class RouteCallback
{
    private Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }
}