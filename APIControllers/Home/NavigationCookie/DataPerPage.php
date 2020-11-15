<?php


namespace APIControllers\Home\NavigationCookie;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Exceptions\Logger as LoggerEx;

use core\Classes\ControllersInterface\APIController;
use core\Classes\Request\HttpRequest;
use core\Classes\Cookie;
use Lib\Singles\Logger;
use Lib\Singles\PrimitiveValidator;


/**
 * API предназначен для установки cookie с количеством отображаемых данных на странице
 *
 * API result:
 * - ok
 * - 1 - Элемент NAVIGATION_SORTING['___'] не существует в указанной константе
 * - 2 - Некорретное значение параметра 'data_per_page'
 * - 3 - Произошла ошибка при установке cookie
 */
class DataPerPage extends APIController
{

    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
     */
    public function doExecute(): void
    {
        list(
            'view_name'     => $viewName,
            'data_per_page' => $dataPerPage
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['view_name', 'data_per_page']);

        // Проверка существования указанной view
        if (!isset(NAVIGATION_SORTING[$viewName])) {
            $this->logAndErrorExit(1,"Элемент NAVIGATION_SORTING['{$viewName}'] не существует в указанной константе");
        }

        $primitiveValidator = new PrimitiveValidator();

        try {
            $primitiveValidator->validateSomeInclusions($dataPerPage, '25', '50', '75');
        } catch (PrimitiveValidatorEx $e) {
            $this->logAndExceptionExit(2, $e, "Некорретное значение параметра 'data_per_page'");
        }

        // Установка cookie
        if (!Cookie::setNavigationDataPerPage($viewName, $dataPerPage)) {
            $this->logAndErrorExit(3, 'Произошла ошибка при установке cookie');
        }

        // Все прошло успешно
        $this->successExit();
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws LoggerEx
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS . '/NavigationCookieDataPerPage.log');
    }
}