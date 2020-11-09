<?php


namespace PageControllers;

use core\Classes\ControllersInterface\PageController;

use APIControllers\Home\FileNeedsSetter;
use core\Classes\ControllersInterface\APIController;
use core\Classes\Request\HttpRequest;
use Lib\ErrorTransform\ErrorTransformer;
use Lib\ErrorTransform\Handlers\ErrorExceptionHandler;
use Lib\Exceptions\DataBase;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;
use Lib\Singles\Logger;

//---------------------------------------

use Lib\TableMappings\TableMappingsXMLHandler;


class Test extends PageController
{

    public function doExecute(): void
    {
        $req = HttpRequest::getInstance();

        $a = $req->hasPOST('a');

        vd($a);

        $b = $req->a->b->c->d;

        vd($b);
    }
}


class Wrapper
{
}