<?php


namespace ControllersClasses;
use core\Classes\Request\HttpRequest;


class Test extends Controller
{

    public function doExecute(): void
    {
        $request = HttpRequest::getInstance();

        $a = $request->isGET();
        $b = $request->isPOST();

        vd($a);
        vd($b);
    }
}