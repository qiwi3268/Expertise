<?php


namespace ControllersClasses;
use core\Classes\Session;


class Login extends Controller
{

    /**
     * Реализация абстрактного метода
     *
     */
    public function doExecute(): void
    {
        Session::deleteUser();
    }
}