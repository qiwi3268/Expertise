<?php


namespace Lib\Singles;


/**
 *  Предназначен для полученя данных о пагинации:
 *
 * - количество страниц
 * - текущую страницу
 * - флаг существования данных
 * - флаг существования предыдущей страницы
 * - флаг существования следующей страницы
 *
 */
class Pagination
{

    /**
     * Общее количество данных
     *
     */
    private int $dataCount;

    /**
     * Колиство страниц
     *
     */
    private int $pageCount;

    /**
     * Текущая страница
     *
     */
    private int $currentPage;


    /**
     * Конструктор класса
     *
     * @param int $dataCount общее количество данных
     * @param int $dataPerPage количесто данных на странице
     * @param int $currentPage номер текущей страницы
     */
    public function __construct(int $dataCount, int $dataPerPage, int $currentPage)
    {
        $pageCount = (int)ceil($dataCount / $dataPerPage); // т.к. ceil возвращает float

        // В случае отрицательный страницы - отображаем её модуль
        $currentPage = abs($currentPage);

        // В случае, если указана страница больше существующих - показываем последнюю
        // current_page может быть 0, если нет данных
        if ($currentPage > $pageCount) {
            $currentPage = $pageCount;
        }

        $this->dataCount = $dataCount;
        $this->pageCount = $pageCount;
        $this->currentPage = $currentPage;
    }


    /**
     * Предназначен для получения количества страниц
     *
     * @return int количество страниц
     */
    public function getPageCount(): int
    {
        return $this->pageCount;
    }


    /**
     * Предназначен для проверки существования данных (выборки из БД)
     *
     * Метод имеет больше вспомогательный характер, поскольку общее количество
     * данных передается с клиентского кода
     *
     * @return bool <b>true</b> данные существуют<br>
     * <b>false</b> не существуют
     */
    public function checkDataExist(): bool
    {
        return $this->dataCount > 0;
    }


    /**
     * Предназначен для получения текущей страницы
     *
     * С учетом возможного некорретного ввода пользователя
     *
     * @return int текущая страница
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }


    /**
     * Предназначен для проверки существования предыдущей страницы
     *
     * @return bool <b>true</b> существует<br>
     * <b>false</b> не существует
     */
    public function checkIssetPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }


    /**
     * Предназначен для проверки существования следующей страницы
     *
     * @return bool <b>true</b> существует<br>
     * <b>false</b> не существует
     */
    public function checkIssetNextPage(): bool
    {
        return $this->currentPage < $this->pageCount;
    }
}