<?php


namespace ControllersClasses;


class Test extends Controller
{

    public function doExecute(): void
    {
        $str = 'application';

        $a = snakeToCamelCase($str, false);

        vd($a);
    }
}