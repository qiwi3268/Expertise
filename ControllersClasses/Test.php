<?php


namespace ControllersClasses;
use core\Classes\Request\HttpRequest;


class Test extends Controller
{

    public function doExecute(): void
    {
        $a = HttpRequest::getInstance();

        vd($a);
    }
}