<?php


class Session{

    // ------------------------------------- Блок сессии в контексте пользователя -------------------------------------

    // Предназначен для создания сессии пользователя при авторизации
    // Принимает параметры-----------------------------------
    // userAssoc array : данные о пользователе
    // userRole  array : данные о ролях пользователя
    //
    static public function createUser(array $userAssoc, array $userRole):void {

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
            'authorized' => !empty(self::getUserRoles()),
            'admin'      => in_array(_ROLE['ADM'], self::getUserRoles(), true),
            'applicant'  => in_array(_ROLE['APP'], self::getUserRoles(), true)
        ];
    }


    // Предназначен для удаления данных о пользователе из сессии
    //
    static public function deleteUser():void {

        unset($_SESSION['user_info'], $_SESSION['flags'], $_SESSION['role_in_application']);
    }


    // Предназначен для создания (хранения) в сесии заявителя id-заявлений, в которых он является автором
    //
    static public function createAuthorRoleApplicationIds(array $applicationsIds):void {
        $_SESSION['role_in_application'][_ROLE_IN_APPLICATION['AUTHOR']] = $applicationsIds;
    }


    // Предназначен для добавления id-заявления к списку тех, в которых он является автором
    //
    static public function addAuthorRoleApplicationId(int $applicationId):void {

        if(isset($_SESSION['role_in_application'][_ROLE_IN_APPLICATION['AUTHOR']])){

            // Добавляем id в начало массива с заявлениями
            array_unshift($_SESSION['role_in_application'][_ROLE_IN_APPLICATION['AUTHOR']], $applicationId);
        }else{

            $_SESSION['role_in_application'][_ROLE_IN_APPLICATION['AUTHOR']] = [$applicationId];
        }
    }

    // Предназначен для получения массива id-заявлений, в которых пользователь являтеся автором
    // Возвращает параметры-----------------------------------
    // array  : id-заявлений в сессии
    // null   : в сессии нет записанных id-заявлений
    //
    static public function getAuthorRoleApplicationIds():?array {

        return $_SESSION['role_in_application'][_ROLE_IN_APPLICATION['AUTHOR']] ?? null;
    }




    static public function getUserInfo():array {
        return $_SESSION['user_info'];
    }

    static public function getUserId():int {
        return $_SESSION['user_info']['id'];
    }

    static public function getUserFullFIO():string {

        $F = $_SESSION['user_info']['last_name'];
        $I = $_SESSION['user_info']['first_name'];
        $O = $_SESSION['user_info']['middle_name'];
        return "$F $I $O";
    }

    // Предназначен получения для получения ролей пользователя
    //
    static public function getUserRoles():array {
        return $_SESSION['user_info']['role'];
    }

    // Предназначен для проверки пользователя на заявителя
    // Возвращает параметры-----------------------------------
    // true  : пользователь заявитель
    // false : пользователь не заявитель
    //
    static public function isApplicant():bool {

        if(isset($_SESSION['flags']['applicant']) &&
                 $_SESSION['flags']['applicant'] === true){
            return true;
        }
        return false;
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

}