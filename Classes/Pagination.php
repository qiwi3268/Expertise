<?php


namespace Classes;


// Предназначен для полученя данных о пагинации:
// - количество страниц
// - текущую страницу
// - флаг существования предыдущей страницы
// - флаг существования следующей страницы
//
class Pagination
{

    private int $pageCount;   // Колиство страниц
    private int $currentPage; // Текущая страница


    // Принимает параметры-----------------------------------
    // dataCount   int : общее количество данных
    // dataPerPage int : количесто данных на странице
    // currentPage int : номер текущей страницы
    //
    public function __construct(int $dataCount, int $dataPerPage, int $currentPage)
    {

        $pageCount = (int)ceil($dataCount / $dataPerPage); // т.к. ceil возвращает float

        // В случае отрицательный страницы - отображаем её модуль
        $currentPage = abs($currentPage);

        // В случае, если указана страница больше существующих - показываем последнюю
        if ($currentPage > $pageCount) {
            $currentPage = $pageCount;
        }

        $this->pageCount = $pageCount;
        $this->currentPage = $currentPage;
    }


    // Предназначен для получения количества страниц
    // Возвращает параметры----------------------------------
    // int : количество страниц
    //
    public function getPageCount(): int
    {
        return $this->pageCount;
    }


    // Предназначен для получения текущей страницы, с учетом возможного некорретного ввода пользователя
    // Возвращает параметры----------------------------------
    // int : текущая страница
    //
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }


    // Предназначен для проверки существования предыдущей страницы
    // Возвращает параметры----------------------------------
    // true  : существует
    // false : не существует
    //
    public function checkIssetPreviousPage(): bool
    {
        return $this->currentPage != 1;
    }


    // Предназначен для проверки существования следующей страницы
    // Возвращает параметры----------------------------------
    // true  : существует
    // false : не существует
    //
    public function checkIssetNextPage(): bool
    {
        return $this->currentPage != $this->pageCount;
    }
}