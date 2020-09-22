<?php


namespace core\Classes;

use Classes\Exceptions\PregMatch as PregMatchEx;


/**
 * Предназначен работы с <b>$_SESSION</b>
 *
 * Любая работа с сессией должна производиться через методы этого класса<br>
 * Работать напрямую с глобальным массивом $_SESSION <b>запрещено</b>
 *
 */
class Session
{

    /**
     * Предназначен для создания сессии пользователя
     *
     * @param array $userAssoc ассоциативный массив пользователя
     * @param array $userRoles ассоциативный массив ролей пользователя
     */
    static public function createUser(array $userAssoc, array $userRoles): void
    {
        $_SESSION['user_info'] = [
            'id'          => $userAssoc['id'],
            'first_name'  => $userAssoc['first_name'],
            'middle_name' => $userAssoc['middle_name'],
            'last_name'   => $userAssoc['last_name'],
            'department'  => $userAssoc['department'],
            'position'    => $userAssoc['position']
        ];

        $_SESSION['user_info']['roles'] = [];

        foreach ($userRoles as $role) {
            $_SESSION['user_info']['roles'][] = $role['system_value'];
        }

        $_SESSION['flags'] = [
            'authorized'       => !empty($_SESSION['user_info']['roles']),
            'admin'            => in_array(ROLE['ADM'], $_SESSION['user_info']['roles'], true),
            'applicant'        => in_array(ROLE['APP'], $_SESSION['user_info']['roles'], true),
            'freelance_expert' => in_array(ROLE['FRE_EXP'], $_SESSION['user_info']['roles'], true)
        ];
    }


    /**
     * Предназначен для удаления данных о пользователе из сессии
     *
     */
    static public function deleteUser(): void
    {
        unset($_SESSION['user_info'], $_SESSION['flags'], $_SESSION['role_in_application']);
    }


    /**
     * Предназначен для получения user_info
     *
     * @return array ассоциативный массив user_info
     */
    static public function getUserInfo(): array
    {
        return $_SESSION['user_info'];
    }


    /**
     * Предназначен для получения id пользователя
     *
     * @return int id пользователя
     */
    static public function getUserId(): int
    {
        return $_SESSION['user_info']['id'];
    }


    /**
     * Предназначен для получения полного ФИО пользователя
     *
     * @return string полное ФИО пользователя
     * @throws PregMatchEx
     */
    static public function getUserFullFIO(): string
    {
        return getFIO($_SESSION['user_info'], false);
    }


    /**
     * Предназначен получения для получения ролей пользователя
     *
     * @return array индексный массив ролей. Является значение system_value из БД
     */
    static public function getUserRoles(): array
    {
        return $_SESSION['user_info']['roles'];
    }


    /**
     * Предназначен для проверки пользователя на заявителя
     *
     * @return bool
     */
    static public function isApplicant(): bool
    {
        if (
            isset($_SESSION['flags']['applicant'])
            && $_SESSION['flags']['applicant'] === true
        ) {
            return true;
        }
        return false;
    }


    /**
     * Предназначен для проверки пользователя на внештатного эксперта
     *
     * @return bool
     */
    static public function isFreelanceExpert(): bool
    {
        if (
            isset($_SESSION['flags']['freelance_expert'])
            && $_SESSION['flags']['freelance_expert'] === true
        ) {
            return true;
        }
        return false;
    }



    /**
     * Предназначен для проверки авторизации пользователя
     *
     * @return bool
     */
    static protected function isAuthorized(): bool
    {
        if (
            isset($_SESSION['flags']['authorized'])
            && $_SESSION['flags']['authorized'] === true
        ) {
            return true;
        }
        return false;
    }


    /**
     * Предназначен для установки сообщения об ошибке
     *
     * @param string $text текст ошибки
     */
    static public function setErrorMessage(string $text): void
    {
        $_SESSION['error_message'] = [
            'text'   => $text,
            'isRead' => false
        ];
    }


    /**
     * Предназначен для получения сообщения об ошибке
     *
     * После получения сообщения, оно помечается как "прочитанное", а в следующий заход удаляется
     *
     * @return string|null <b>string</b> текст сообщения<br><b>null</b> сообщение не существует, либо было уже прочитано
     */
    static public function getErrorMessage(): ?string
    {
        if (isset($_SESSION['error_message'])) {

            if ($_SESSION['error_message']['isRead']) {

                unset($_SESSION['error_message']);
            } else {

                $_SESSION['error_message']['isRead'] = true;
                return $_SESSION['error_message']['text'];
            }
        }
        return null;
    }
}