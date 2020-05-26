<?php


class Session{

    // ------------------------------------- Блок сессии в контексте пользователя -------------------------------------

    // Предназначен для создания сессии пользователя при авторизации
    // Принимает параметры-----------------------------------
    // userAssoc array : данные о пользователе
    // userRole  array : данные о ролях пользователя
    //
    static public function createUser(array $userAssoc, array $userRole){

        $_SESSION['user_info'] = [
            'id'          => $userAssoc['id'],
            'first_name'  => $userAssoc['first_name'],
            'middle_name' => $userAssoc['middle_name'],
            'last_name'   => $userAssoc['last_name'],
            'department'  => $userAssoc['department'],
            'position'    => $userAssoc['position']
        ];

        $_SESSION['user_info']['role'] = [];

        foreach($userRole as $role){
            $_SESSION['user_info']['role'][] = $role['system_value'];
        }

        $_SESSION['flags'] = [
            'authorized' => !empty(self::getUserRole()),
            'admin'      => in_array(_ROLES['ADM'], self::getUserRole())
        ];
    }


    // Предназначен для удаления данных о пользователе из сессии
    //
    static public function deleteUser(){

        unset($_SESSION['user_info']);
        unset($_SESSION['flags']);
    }

    static public function getUserInfo():array {
        return $_SESSION['user_info'];
    }

    static public function getUserId():int {
        return $_SESSION['user_info']['id'];
    }

    // Предназначен получения для получения ролей пользователя
    //
    static public function getUserRole():array {
        return $_SESSION['user_info']['role'];
    }

    // Предназначен для проверки пользователя на заявителя
    // Возвращает параметры-----------------------------------
    // true  : пользователь заявитель
    // false : пользователь не заявитель (сотрудник)
    //
    static public function isApplicant():bool {
        $roles = self::getUserRole();
        return in_array(_ROLES['APP'], $roles, true);
    }


    // Предназначен для проверки авторизации пользователя
    // Возвращает параметры-----------------------------------
    // true  : пользователь авторизован
    // false : пользователь не авторизован
    //
    static protected function isAuthorized():bool {

        if(isset($_SESSION['flags']['authorized']) &&
                 $_SESSION['flags']['authorized'] === true){
            return true;
        }
        return false;
    }

    // ------------------------------------- Блок сессии в контексте заявления -------------------------------------

    // TODO со временем переделать, принимать assoc...
    static public function createApplicationContext(int $applicationId){

        $_SESSION['application_context'] = [
            'id' => $applicationId
        ];
    }

    static public function deleteApplicationContext(){
        unset($_SESSION['application_context']);
    }

    static public function getApplicationId():int {
        return $_SESSION['application_context']['id'];
    }





}