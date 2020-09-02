<?php

use Lib\Exceptions\DataBase as DataBaseEx;
use core\Classes\Session;
use Tables\users;


//API result:
//	1 - Нет обязательных параметров POST запроса
//      {result}
//	2 - Не подходит логин/пароль
//      {result}
//  3 - Учетная запись забанена
//      {result}
//  4 - Пользователь не имеет роль в системе
//      {result}
//	5 - Авторизация и создание сессии прошло успешно
//      {result, ref : ссылка на домашнюю страницу}
//	6 - Ошибка при запросе в БД
//      {result, message : текст ошибки, code: код ошибки}
//	7 - Непредвиденная ошибка
//      {result, message : текст ошибки, code: код ошибки}
//
if (checkParamsPOST('login', 'password')) {

    try {

        /** @var string $P_login логин */
        /** @var string $P_password пароль */
        extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

        $userAssoc = users::getAssocByLogin($P_login);

        // Пользователь сущесвует
        if (!is_null($userAssoc)) {

            // Пароль введен верно
            if (password_verify($P_password, $userAssoc['password'])) {

                // Учетная запись пользователя забанена
                if ($userAssoc['is_banned']) {

                    exit(json_encode(['result' => 3]));
                } else {

                    // Обнуляем счетчик неверно введенных паролей
                    users::zeroingIncorrectPasswordInputById($userAssoc['id']);
                }

                $userRoles = users::getRolesById($userAssoc['id']);

                // Пользователь не имеет ролей
                if (is_null($userRoles)) {

                    exit(json_encode(['result' => 4]));
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
            users::incrementIncorrectPasswordInputById($userAssoc['id']);

            // Максимально допустимое количество неверно введенных паролей
            $maxCountIncorrectPassword = 4;

            if ($userAssoc['num_incorrect_password_input'] + 1 > $maxCountIncorrectPassword) {

                // Баним пользователя
                users::banById($userAssoc['id']);
            }
        }

        exit(json_encode(['result' => 2]));

    } catch (DataBaseEx $e) {

        exit(json_encode([
            'result'  => 6,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    } catch (Exception $e) {

        exit(json_encode([
            'result'  => 7,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }
}
exit(json_encode(['result' => 1]));
