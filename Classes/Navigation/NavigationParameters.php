<?php


namespace Classes\Navigation;

use core\Classes\Cookie;


/**
 * Предназначен для получения параметров навигационной страницы
 *
 * Название столбца, по которому происходит сортировка,<br>
 * тип сортировки (по возрастанию / убыванию),<br>
 * количество данных на странице<br>
 *
 * Данные берутся из cookie пользователя. Если их нет - значения по умолчанию
 *
 */
class NavigationParameters
{

    /**
     * Значения по умолчанию, если нет cookie
     *
     * SORT_NAME определяеся динамически
     *
     */
    private const DEFAULT_SORT_TYPE = 'ASC';
    private const DEFAULT_DATA_PER_PAGE = 25;

    /**
     * Массив с параметрами страницы
     *
     * Имеет ключи:<br>
     * sort_name, sort_type, data_per_page
     *
     */
    private array $parameters;


    /**
     * Конструктор класса
     *
     * @param string $viewName название открытой view
     */
    public function __construct(string $viewName)
    {
        $cookie = Cookie::getNavigationView($viewName);
        $key = array_key_first(NAVIGATION_SORTING[$viewName]); // По умолчанию sort_name берется как первый элемент для viewName

        $this->parameters['sort_name'] = $cookie['sort_name'] ?? NAVIGATION_SORTING[$viewName][$key]['column_name'];
        $this->parameters['sort_type'] = $cookie['sort_type'] ?? self::DEFAULT_SORT_TYPE;
        $this->parameters['data_per_page'] = $cookie['data_per_page'] ?? self::DEFAULT_DATA_PER_PAGE;
    }


    /**
     * Предназначен для получения названия столбца из БД, по которому происходит сортировка
     *
     * @return string имя столбца из БД
     */
    public function getSortName(): string
    {
        return $this->parameters['sort_name'];
    }


    /**
     * Предназначен для получения типа сортировки (по возрастанию или убываннию)
     *
     * @return string тип сортировки
     */
    public function getSortType(): string
    {
        return $this->parameters['sort_type'];
    }


    /**
     * Предназначен для получения количества данных на странице
     *
     * @return int количество данных на странице
     */
    public function getDataPerPage(): int
    {
        return $this->parameters['data_per_page'];
    }
}
