<?php


class Cookie{

    //todo принимать время жизни кук
    static public function setNavigationSort(string $viewName, string $sortName, string $sortType):void {
    
    }
    
    static public function setNavigationDataPerPage(string $viewName, int $count):void {
    
    }
    
    static public function getNavigationView(string $viewName):?array {
    
        return $_COOKIE['navigation'][$viewName] ?? null;
    }
}