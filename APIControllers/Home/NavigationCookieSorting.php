<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;

use core\Classes\ControllersInterface\APIController;
use core\Classes\Request\HttpRequest;
use core\Classes\Cookie;
use Lib\Singles\Logger;
use Lib\Singles\PrimitiveValidator;


/**
 * API предназначен для установки cookie с названием столбца и типом сортировки данных
 *
 * API result:
 * - ok
 * - 1 - Элемент NAVIGATION_SORTING['___']['___'] не существует в указанной константе
 * - 2 - Некорретное значение параметра 'sort_type'
 * - 3 - Произошла ошибка при установке cookie
 */
class NavigationCookieSorting extends APIController
{

    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
     */
    public function doExecute(): void
    {
        list(
            'view_name' => $viewName,
            'sort_name' => $sortName,
            'sort_type' => $sortType
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['view_name', 'sort_name', 'sort_type']);

        // Проверка существования указанной view
        if (!isset(NAVIGATION_SORTING[$viewName][$sortName])) {
            $this->logAndErrorExit(1,"Элемент NAVIGATION_SORTING['{$viewName}']['{$sortName}'] не существует в указанной константе");
        }

        $primitiveValidator = new PrimitiveValidator();

        try {
            $primitiveValidator->validateSomeInclusions($sortType, 'ASC', 'DESC');
        } catch (PrimitiveValidatorEx $e) {
            $this->logAndExceptionExit(2, $e, "Некорретное значение параметра 'sort_type'");
        }

        // Установка cookie
        if (
            !Cookie::setNavigationSortName($viewName, $sortName)
            || !Cookie::setNavigationSortType($viewName, $sortType)
        ) {
            $this->logAndErrorExit(3, 'Произошла ошибка при установке cookie');
        }

        // Все прошло успешно
        $this->successExit();
    }


    /**
     * Реализация абстрактного метода
     *
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS, 'NavigationCookieSorting.log');
    }
}