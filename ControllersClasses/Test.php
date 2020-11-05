<?php


namespace ControllersClasses;

use Lib\ErrorTransform\ErrorTransformer;
use Lib\ErrorTransform\Handlers\ErrorExceptionHandler;


class Test extends Controller
{

    public function doExecute(): void
    {
        $err = new ErrorTransformer(new ErrorExceptionHandler(), true);
        unset($err);

        str_replace();
    }


    public function test()
    {

    }






}