<?php


namespace APIControllers;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Logger as LoggerEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use core\Classes\Session;
use Lib\Singles\Logger;
use Tables\user;


/**
 * API result:
 * - ok - ['ref']
 * - 1  - Учетная запись заблокирована
 * - 2  - Пользователь не имеет ролей в системе
 * - 3  - Не подходит логин / пароль
 *
 */
class Login extends APIController
{

    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
     * @throws DataBaseEx
     */
    public function doExecute(): void
    {

        list(
            'login'    => $login,
            'password' => $password
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['login', 'password']);

        $userAssoc = user::getAssocByLogin($login);

        // Пользователь существует
        if (!is_null($userAssoc)) {

            // Пароль введен верно
            if (password_verify($password, $userAssoc['password'])) {

                // Учетная запись пользователя заблокирована
                if ($userAssoc['is_banned']) {
                    $this->errorExit(1, 'Учетная запись заблокирована');
                }

                // Обнуляем счетчик неверно введенных паролей
                user::zeroingIncorrectPasswordInputById($userAssoc['id']);

                $userRoles = user::getAllRolesAssocByUserId($userAssoc['id']);

                // Пользователь не имеет ролей
                if (is_null($userRoles)) {
                    $this->logAndErrorExit(2, 'Пользователь не имеет ролей в системе');
                }

                // Создание сессии пользователя
                Session::createUser($userAssoc, $userRoles);

                // Авторизация прошла успешно
                $this->successExit(['ref' => '/home/navigation']);
            }

            // Инкрементируем счетчик неверно введенных паролей
            user::incrementIncorrectPasswordInputById($userAssoc['id']);

            // Максимально допустимое количество неверно введенных паролей
            $maxCountIncorrectPassword = 4;

            if ($userAssoc['num_incorrect_password_input'] + 1 > $maxCountIncorrectPassword) {

                // Блокируем пользователя
                user::setBanById($userAssoc['id']);
            }
        }
        $this->errorExit(3, 'Не подходит логин / пароль');
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws LoggerEx
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS . '/Login.log');
    }
}