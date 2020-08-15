<?php


// Класс предназначен для получения параметров навигационной страницы:
// - название столбца, по которому отсортированы данные
// - вид сортировки (по возрастанию/убыванию)
// - количество данных на странице
// Данные берутся из cookie пользователя. Если их нет - по умолчанию
//
class NavigationParameters{

    // Значения по умолчанию, если нет cookie
    // SORT_NAME определяеся динамически
    private const DEFAULT_SORT_TYPE = 'ASC';
    private const DEFAULT_DATA_PER_PAGE = 25;
    
    // Массив с параметрами страницы. Имеет ключи:
    // sort_name, sort_type, data_per_page
    private array $parameters;
    
    
    // Принимает параметры-----------------------------------
    // viewName string : название открытой view
    //
    public function __construct(string $viewName){
        
        $cookie = Cookie::getNavigationView($viewName);
        $key = array_key_first(_NAVIGATION_SORTING[$viewName]); // По умолчанию sort_name берется как первый элемент для viewName
    
        $this->parameters['sort_name'] = $cookie['sort_name'] ?? _NAVIGATION_SORTING[$viewName][$key]['column_name'];
        $this->parameters['sort_type'] = $cookie['sort_type'] ?? self::DEFAULT_SORT_TYPE;
        $this->parameters['data_per_page'] = $cookie['data_per_page'] ?? self::DEFAULT_DATA_PER_PAGE;
    }
    
    
    // get'теры параметров страницы
    // Возвращают параметры----------------------------------
    // string : параметр сортировки данных
    //
    public function getSortName():string {
        return $this->parameters['sort_name'];
    }
    public function getSortType():string {
        return $this->parameters['sort_type'];
    }
    // Возвращает параметры----------------------------------
    // int : количество данных на странице
    //
    public function getDataPerPage():int {
        return $this->parameters['data_per_page'];
    }
}
