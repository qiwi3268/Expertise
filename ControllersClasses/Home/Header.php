<?php


namespace ControllersClasses\Home;

use functions\Exceptions\Functions as FunctionsEx;

use ControllersClasses\Controller;
use core\Classes\Session;


class Header extends Controller
{

    /**
     * Реализация абстрактного метода
     *
     * @throws FunctionsEx
     */
    public function doExecute(): void
    {
        $this->VT->setValue('user_FIO', Session::getUserFullFIO());
    }
}