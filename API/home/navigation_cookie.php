<?php


use core\Classes\Cookie;


// API предназначен для установки cookie с параметрами страницы:
// - количество отображаемых элементов на странице
// ИЛИ
// - название столбца для сортировки
// - тип сортировки
// API result:
//  1 - Нет обязательных параметров POST запроса
//      {result, error_message : текст ошибки}
//  2 - Одновременно переданы данные о сортировке и количестве элементов на странице
//      {result, error_message : текст ошибки}
//  3 - Передан некорректный параметр: name, со значением: value
//      {result, error_message : текст ошибки}
//  4 - Произошла ошибка при установке cookie
//      {result, error_message : текст ошибки}
//  5 - Все операции прошли успешно
//      {result}
//
if (!checkParamsPOST('view_name', 'data_per_page') && !checkParamsPOST('view_name', 'sort_name', 'sort_type')) {
    exit(json_encode([
        'result' => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}


/** @var string $P_view_name */
/** @var string $P_data_per_page */
// ИЛИ
/** @var string $P_view_name */
/** @var string $P_sort_name */
/** @var string $P_sort_type */
extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');


if (isset($P_data_per_page) && (isset($P_sort_name) || isset($P_sort_type))) {
    exit(json_encode([
        'result' => 2,
        'error_message' => 'Одновременно переданы данные о сортировке и количестве элементов на странице'
    ]));
}

// Проверка существования указанной view
if (!isset(NAVIGATION_SORTING[$P_view_name])) exit_incorrectValue('view_name', $P_view_name);

// Определяем тип устанавливаемых cookie: количество отображаемых элементов на странице ИЛИ сортировка
if (isset($P_data_per_page)) {

    // Проверка data_per_page на int 25/50/75
    // Ошибка, если НЕ int ИЛИ не равен ни одному из доступных чисел
    if (filter_var($P_data_per_page, FILTER_VALIDATE_INT) === false ||
        ($P_data_per_page != 25 && $P_data_per_page != 50 && $P_data_per_page != 75)) {

        exit_incorrectValue('data_per_page', $P_data_per_page);
    }

    // Установка cookie
    if (!Cookie::setNavigationDataPerPage($P_view_name, $P_data_per_page)) exit_errorSettingCookie();
} else {

    // Проверка sort_name на существование к указанной view
    if (!isset(NAVIGATION_SORTING[$P_view_name][$P_sort_name])) exit_incorrectValue('sort_name', $P_sort_name);
    // Проверка sort_type на ASC/DESC
    if ($P_sort_type != 'ASC' && $P_sort_type != 'DESC') exit_incorrectValue('sort_type', $P_sort_type);

    // Установка cookie
    if (!Cookie::setNavigationSortName($P_view_name, $P_sort_name)) exit_errorSettingCookie();
    if (!Cookie::setNavigationSortType($P_view_name, $P_sort_type)) exit_errorSettingCookie();
}

function exit_incorrectValue(string $name, string $value): void
{
    exit(json_encode([
        'result' => 3,
        'error_message' => "Передан некорректный параметр: $name, со значением: $value"
    ]));
}

function exit_errorSettingCookie(): void
{
    exit(json_encode([
        'result' => 4,
        'error_message' => 'Произошла ошибка при установке cookie'
    ]));
}

exit(json_encode(['result' => 5]));