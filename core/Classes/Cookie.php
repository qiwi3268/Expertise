<?php


// Предназначен для установки и получения cookie
//
class Cookie{
    
<<<<<<< HEAD
    
    // Блок страницы навигации /home/navigation ------------------------------------------------
=======

    // -----------------------------------------------------------------------------------------
    // Блок навигационной страницы /home/navigation
    // -----------------------------------------------------------------------------------------
    //
    // set'теры
    // Принимает параметры-----------------------------------
    // viewName    int : название view
    // sortName string : имя столбца для сортировки
    // expiresDays int : время жизни cookie. По умолчанию - 10 дней
    // Возвращает параметры----------------------------------
    // true  : cookie успешно установились
    // false : в противном случае
>>>>>>> 5b015d9495abdca6a19a460370085b00167fbfbb
    //
    static public function setNavigationSortName(string $viewName, string $sortName, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][sort_name]", $sortName, self::getExpires($expiresDays)) ? true : false;
    }
<<<<<<< HEAD
    
    static public function setNavigationSortType(string $viewName, string $sortType, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][sort_type]", $sortType, self::getExpires($expiresDays)) ? true : false;
    }
    
    static public function setNavigationDataPerPage(string $viewName, int $dataPerPage, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][data_per_page]", $dataPerPage, self::getExpires($expiresDays)) ? true : false;
    }
    
    
    
    static public function getNavigationView(string $viewName):?array {
    
=======
    // Принимает параметры-----------------------------------
    // viewName    int : название view
    // sortType string : тип сортировки (ASC/DESC)
    // expiresDays int : время жизни cookie. По умолчанию - 10 дней
    // Возвращает параметры----------------------------------
    // true  : cookie успешно установились
    // false : в противном случае
    //
    static public function setNavigationSortType(string $viewName, string $sortType, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][sort_type]", $sortType, self::getExpires($expiresDays)) ? true : false;
    }
    // Принимает параметры-----------------------------------
    // viewName    int : название view
    // dataPerPage int : количество отображаемых элементов на странице
    // expiresDays int : время жизни cookie. По умолчанию - 10 дней
    // Возвращает параметры----------------------------------
    // true  : cookie успешно установились
    // false : в противном случае
    //
    static public function setNavigationDataPerPage(string $viewName, int $dataPerPage, int $expiresDays = 10):bool {
        return setcookie("navigation[$viewName][data_per_page]", $dataPerPage, self::getExpires($expiresDays)) ? true : false;
    }
    // Предназначен для получения ассоциативного массива данных для навигационной страницы
    // Принимает параметры-----------------------------------
    // viewName    int : название view
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив
    // null : в противном случае
    //
    static public function getNavigationView(string $viewName):?array {
>>>>>>> 5b015d9495abdca6a19a460370085b00167fbfbb
        return $_COOKIE['navigation'][$viewName] ?? null;
    }
    
    
<<<<<<< HEAD
=======
    // Предназначен для перевода жизни cookie из дней в метку Unix
    // Принимает параметры-----------------------------------
    // expiresDays int : количество дней
    // Возвращает параметры----------------------------------
    // int : количество секунд
    //
>>>>>>> 5b015d9495abdca6a19a460370085b00167fbfbb
    static private function getExpires(int $expiresDays):int {
        return time() + 60 * 60 * 24 * $expiresDays;
    }
}