<?php

use Lib\Exceptions\DataBase as DataBaseEx;
use core\Classes\Session;
use Tables\user;


//API result:
//	1 - Нет обязательных параметров POST запроса
//      {result, error_message : текст ошибки}
//	2 - Не подходит логин / пароль
//      {result, error_message : текст ошибки}
//  3 - Учетная запись заблокирована
//      {result, error_message : текст ошибки}
//  4 - Пользователь не имеет ролей в системе
//      {result, error_message : текст ошибки}
//	5 - Авторизация и создание сессии прошло успешно
//      {result, ref : ссылка на домашнюю страницу}
//	6 - Непредвиденная ошибка
//      {result, message : текст ошибки, code: код ошибки}
//

// Проверка наличия обязательных параметров
if (!checkParamsPOST('login', 'password')) {

    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try {

    /** @var string $P_login логин */
    /** @var string $P_password пароль */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    $userAssoc = user::getAssocByLogin($P_login);

    // Пользователь существует
    if (!is_null($userAssoc)) {

        // Пароль введен верно
        if (password_verify($P_password, $userAssoc['password'])) {

            // Учетная запись пользователя заблокирована
            if ($userAssoc['is_banned']) {

                exit(json_encode([
                    'result'        => 3,
                    'error_message' => 'Учетная запись заблокирована'
                ]));
            }

            // Обнуляем счетчик неверно введенных паролей
            user::zeroingIncorrectPasswordInputById($userAssoc['id']);

            $userRoles = user::getAllRolesAssocByUserId($userAssoc['id']);

            // Пользователь не имеет ролей
            if (is_null($userRoles)) {

                exit(json_encode([
                    'result'        => 4,
                    'error_message' => 'Пользователь не имеет ролей в системе'
                ]));
            }

            // Создание сессии пользователя
            Session::createUser($userAssoc, $userRoles);

            // Авторизация прошла успешно
            exit(json_encode([
                'result' => 5,
                'ref'    => '/home/navigation'
            ]));
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

    exit(json_encode([
        'result'        => 2,
        'error_message' => 'Не подходит логин / пароль'
    ]));

} catch (Exception $e) {

    exit(json_encode([
        'result'  => 6,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}

