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

        $URIS = [
            "home/expertise_cards/applicat1ion/view?test[]=1&test[]=2&id_document=1905",
            "home/expertise_cards/application/actions/action_2?id_document=1905",
            "home/application/create?id_document=1905",
            "home/application/create?id_document=1905",
        ];

        $uri = $URIS[0];
        $a = URIParser::parseExpertiseCard($uri);
        vd($a);

        $uri = $URIS[1];
        $a = URIParser::parseActionPage($uri);
        vd($a);

        $uri = $URIS[1];
        $a = URIParser::parseAPIActionExecutor($uri);
        vd($a);

        foreach ($URIS as $URI) {
            vd(URIParser::parse($URI));
        }
    }
}


class Wrapper
{
}