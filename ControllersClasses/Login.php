<?php


namespace ControllersClasses;
use core\Classes\Session;


class Login extends Controller
{

    /**
     * Реализация абстрактного метода
     *
     */
    public function execute(): void
    {
        Session::deleteUser();
    }
}