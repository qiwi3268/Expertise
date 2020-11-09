<?php


namespace PageControllers;

use core\Classes\ControllersInterface\PageController;
use core\Classes\Session;


class Login extends PageController
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