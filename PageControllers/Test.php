<?php


namespace PageControllers;

use APIControllers\Home\FileUploader\Types\Type1;
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
use Lib\Singles\URIParser;

//---------------------------------------

use Lib\Singles\PrimitiveValidator;
use Lib\TableMappings\TableMappingsXMLHandler;


class Test extends PageController
{

    public function doExecute(): void
    {
        $uri = "/home/expertise_cards/application/view?test[]=1&test[]=2&id_document=1905";
        $a = URIParser::parseExpertiseCard($uri);
        vd($a);
    }
}


class Wrapper
{
}