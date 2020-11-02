<?php


namespace Classes\RouteCallbacks;

use Lib\Exceptions\ViewModes as ViewModesEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use ReflectionException;

use core\Classes\Session;
use Lib\ViewModes\ViewModes;


/**
 * Предназначен для проверки доступа пользователя к режиму просмотра, на который
 * переходит пользователь
 *
 * Для работы класса должны быть определены константы:
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_VIEW_MODE
 * - CURRENT_DOCUMENT_ID (Для внутренней работы методов проверки доступа)
 *
 */
class AccessToViewModeChecker
{

    /**
     * Предназначен для проверки доступа пользователя к режиму просмотра
     *
     * В случае отсутствия доступа - перенаправляет на навигационную страницу
     * с сообщением об ошибке
     *
     * @throws ViewModesEx
     * @throws XMLValidatorEx
     * @throws ReflectionException
     */
    static public function checkAccessViewMode(): void
    {
        $viewModes = ViewModes::getInstance(CURRENT_DOCUMENT_TYPE);

        if (!$viewModes->checkAccessToViewMode(CURRENT_VIEW_MODE)) {

            Session::setErrorMessage("Режим просмотра, на который Вы собираетесь перейти - недоступен");
            header('Location: /home/navigation');
            exit();
        }
    }
}