<?php


// Класс предназначен для получения параметров навигационной страницы:
// - название столбца, по которому отсортированы данные
// - вид сортировки (по возрастанию/убыванию)
// - количество данных на странице
// Данные берутся из cookie пользователя. Если их нет - по умолчанию
//
class NavigationParameters{

    // Значения по умолчанию, если нет cookie
    private const DEFAULT_SORT_NAME = 'numerical_name';
    private const DEFAULT_SORT_TYPE = 'ASC';
    private const DEFAULT_DATA_PER_PAGE = 10;
    
    // Массив с параметрами страницы. Имеет ключи:
    // sort_name, sort_type, data_per_page
    private array $parameters;
    
    public function __construct(string $viewName){
        
        $navigationCookie = Cookie::getNavigationView($viewName);
        
        if(is_null($navigationCookie)){
            $this->parameters['sort_name'] = self::DEFAULT_SORT_NAME;
            $this->parameters['sort_type'] = self::DEFAULT_SORT_TYPE;
            $this->parameters['data_per_page'] = self::DEFAULT_DATA_PER_PAGE;
        }else{
            $this->parameters = $navigationCookie;
        }
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
