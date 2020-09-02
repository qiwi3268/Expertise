<?php


namespace core\Classes;


class Session
{

    // ------------------------------------- Блок сессии в контексте пользователя -------------------------------------

    // Предназначен для создания сессии пользователя при авторизации
    // Принимает параметры-----------------------------------
    // userAssoc array : данные о пользователе
    // userRole  array : данные о ролях пользователя
    //
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
            'authorized' => !empty(self::getUserRoles()),
            'admin'      => in_array(ROLE['ADM'], self::getUserRoles(), true),
            'applicant'  => in_array(ROLE['APP'], self::getUserRoles(), true)
        ];
    }


    // Предназначен для удаления данных о пользователе из сессии
    //
    static public function deleteUser(): void
    {
        unset($_SESSION['user_info'], $_SESSION['flags'], $_SESSION['role_in_application']);
    }


    static public function getUserInfo(): array
    {
        return $_SESSION['user_info'];
    }

    static public function getUserId(): int
    {
        return $_SESSION['user_info']['id'];
    }

    static public function getUserFullFIO(): string
    {
        list('last_name' => $F, 'first_name' => $I, 'middle_name' => $O) = $_SESSION['user_info'];
        return "{$F} {$I} {$O}";
    }

    // Предназначен получения для получения ролей пользователя
    //
    static public function getUserRoles(): array
    {
        return $_SESSION['user_info']['roles'];
    }

    // Предназначен для проверки пользователя на заявителя
    // Возвращает параметры-----------------------------------
    // true  : пользователь заявитель
    // false : пользователь не заявитель
    //
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


    // Предназначен для проверки авторизации пользователя
    // Возвращает параметры-----------------------------------
    // true  : пользователь авторизован
    // false : пользователь не авторизован
    //
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

}