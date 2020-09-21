<?php

use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;

use core\Classes\Cookie;
use Lib\Singles\PrimitiveValidator;


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
//  5 - Все прошло успешно
//      {result}
//  6 - Непредвиденная ошибка
//      {result, message : текст ошибки, code: код ошибки}

if (
    !checkParamsPOST('view_name', 'data_per_page')
    && !checkParamsPOST('view_name', 'sort_name', 'sort_type')
) {

    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try {

    /** @var string $P_view_name */
    /** @var string $P_data_per_page */
    // ИЛИ
    /** @var string $P_view_name */
    /** @var string $P_sort_name */
    /** @var string $P_sort_type */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    $primitiveValidator = new PrimitiveValidator();

    if (
        isset($P_data_per_page)
        && (isset($P_sort_name) || isset($P_sort_type))
    ) {

        exit(json_encode([
            'result'        => 2,
            'error_message' => 'Одновременно переданы данные о сортировке и количестве элементов на странице'
        ]));
    }

    // Проверка существования указанной view
    if (!isset(NAVIGATION_SORTING[$P_view_name])) {
        exit_incorrectValue('view_name', $P_view_name);
    }

    // Определяем тип устанавливаемых cookie: количество отображаемых элементов на странице ИЛИ сортировка
    if (isset($P_data_per_page)) {

        try {
            $primitiveValidator->validateSomeInclusions($P_data_per_page, '25', '50', '75');
        } catch (PrimitiveValidatorEx $e) {
            exit_incorrectValue('data_per_page', $P_data_per_page);
        }

        // Установка cookie
        if (!Cookie::setNavigationDataPerPage($P_view_name, $P_data_per_page)) exit_errorSettingCookie();
    } else {

        // Проверка sort_name на существование к указанной view
        if (!isset(NAVIGATION_SORTING[$P_view_name][$P_sort_name])) {
            exit_incorrectValue('sort_name', $P_sort_name);
        }

        try {
            $primitiveValidator->validateSomeInclusions($P_sort_type, 'ASC', 'DESC');
        } catch (PrimitiveValidatorEx $e) {
            exit_incorrectValue('sort_type', $P_sort_type);
        }

        // Установка cookie
        if (!Cookie::setNavigationSortName($P_view_name, $P_sort_name)) exit_errorSettingCookie();
        if (!Cookie::setNavigationSortType($P_view_name, $P_sort_type)) exit_errorSettingCookie();
    }

    // Все прошло успешно
    exit(json_encode(['result' => 5]));

} catch (Exception $e) {

    exit(json_encode([
        'result'  => 6,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}


/**
 * @param string $name имя параметра
 * @param string $value значение параметра
 */
function exit_incorrectValue(string $name, string $value): void
{
    exit(json_encode([
        'result'        => 3,
        'error_message' => "Передан некорректный параметр: {$name}, со значением: {$value}"
    ]));
}


function exit_errorSettingCookie(): void
{
    exit(json_encode([
        'result'        => 4,
        'error_message' => 'Произошла ошибка при установке cookie'
    ]));
}