<?php


namespace ControllersClasses;


class Test extends Controller
{

    public function execute(): void
    {
        $str = 'application';

        $a = snakeToCamelCase($str, false);

        vd($a);
    }
}