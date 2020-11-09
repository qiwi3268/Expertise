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
        $handler = new TableMappingsXMLHandler();
        $level2 = $handler->getLevel2(2, 1);

        $a = $handler->validateLevel2Structure($level2)->getHandledLevel2Value($level2);

        vd($a);
    }
}


class Wrapper
{
}