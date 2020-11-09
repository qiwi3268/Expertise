<?php


namespace PageControllers\Home;

use functions\Exceptions\Functions as FunctionsEx;

use core\Classes\ControllersInterface\PageController;
use core\Classes\Session;


class Header extends PageController
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