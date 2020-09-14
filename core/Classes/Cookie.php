<?php


namespace core\Classes;


/**
 * Предназначен работы с <b>$_COOKIE</b>
 *
 * Любая работа с сессией должна производиться через методы этого класса<br>
 * Работать напрямую с глобальным массивом $_COOKIE <b>запрещено</b>
 *
 */
class Cookie
{

    /**
     * @param string $viewName название view
     * @param string $sortName имя столбца для сортировки
     * @param int $expiresDays
     * @return bool <b>true</b> cookie успешно установились<br><b>false</b> в противном случае
     */
    static public function setNavigationSortName(string $viewName, string $sortName, int $expiresDays = 10): bool
    {
        return setcookie("navigation[$viewName][sort_name]", $sortName, self::getExpires($expiresDays)) ? true : false;
    }


    /**
     * @param string $viewName название view
     * @param string $sortType тип сортировки <b>(ASC/DESC)</b>
     * @param int $expiresDays время жизни cookie. <i>По умолчанию - 10 дней</i>
     * @return bool <b>true</b> cookie успешно установились<br><b>false</b> в противном случае
     */
    static public function setNavigationSortType(string $viewName, string $sortType, int $expiresDays = 10): bool
    {
        return setcookie("navigation[$viewName][sort_type]", $sortType, self::getExpires($expiresDays)) ? true : false;
    }


    /**
     * @param string $viewName название view
     * @param int $dataPerPage количество отображаемых элементов на странице
     * @param int $expiresDays время жизни cookie. <i>По умолчанию - 10 дней</i>
     * @return bool <b>true</b> cookie успешно установились<br><b>false</b> в противном случае
     */
    static public function setNavigationDataPerPage(string $viewName, int $dataPerPage, int $expiresDays = 10): bool
    {
        return setcookie("navigation[$viewName][data_per_page]", $dataPerPage, self::getExpires($expiresDays)) ? true : false;
    }


    /**
     * Предназначен для получения ассоциативного массива данных для навигационной страницы
     *
     * @param string $viewName название view
     * @return array|null <b>array</b> ассоциативный массив cookie навигационной страницы<br><b>null</b> cookie навигационной страницы не существуют
     */
    static public function getNavigationView(string $viewName): ?array
    {
        return $_COOKIE['navigation'][$viewName] ?? null;
    }


    /**
     * Предназначен для перевода жизни cookie из дней в метку Unix
     *
     * @param int $expiresDays количество дней
     * @return int количество секунд
     */
    static private function getExpires(int $expiresDays): int
    {
        return time() + 60 * 60 * 24 * $expiresDays;
    }
}