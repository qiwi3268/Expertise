<?php


// Предназначен для установки и получения cookie
//
class Cookie{
    
    
    // Блок страницы навигации /home/navigation ------------------------------------------------
    //
    static public function setNavigationSortName(string $viewName, string $sortName, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][sort_name]", $sortName, self::getExpires($expiresDays)) ? true : false;
    }
    
    static public function setNavigationSortType(string $viewName, string $sortType, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][sort_type]", $sortType, self::getExpires($expiresDays)) ? true : false;
    }
    
    static public function setNavigationDataPerPage(string $viewName, int $dataPerPage, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][data_per_page]", $dataPerPage, self::getExpires($expiresDays)) ? true : false;
    }
    
    
    
    static public function getNavigationView(string $viewName):?array {
    
        return $_COOKIE['navigation'][$viewName] ?? null;
    }
    
    
    static private function getExpires(int $expiresDays):int {
        return time() + 60 * 60 * 24 * $expiresDays;
    }
}