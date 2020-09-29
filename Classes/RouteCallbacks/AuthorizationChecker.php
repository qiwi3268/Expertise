<?php


namespace Classes\RouteCallbacks;

use core\Classes\Session;


/**
 * Предназначен для проверки авторизации пользователя
 *
 */
class AuthorizationChecker
{

    /**
     * Предназначен для проверки авторизации пользователя
     *
     * В случае отсутствия авторизации - перенаправляет на страницу логина / пароля
     * с сообщением об ошибке
     *
     */
    public function checkAuthorization(): void
    {
        if (!Session::isAuthorized()) {
            Session::setErrorMessage('Для работы в личном кабинете Вам необходимо авторизоваться');
            header('Location: /');
            exit();
        }
    }
}