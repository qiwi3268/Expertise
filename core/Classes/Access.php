<?php


class Access extends Session{


    // Предназначен для проверки авторизации пользователя
    // Редиректит на указанную страницу, если пользователь не авторизован
    //
    static public function authorized(){

        if(!parent::isAuthorized()){

            header('Location: /');
            exit();
        }
    }
}