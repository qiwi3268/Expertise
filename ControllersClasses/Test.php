<?php


namespace ControllersClasses;

use core\Classes\ControllersInterface\APIController;
use Lib\ErrorTransform\ErrorTransformer;
use Lib\ErrorTransform\Handlers\ErrorExceptionHandler;
use Lib\Exceptions\DataBase;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;
use Lib\Singles\Logger;

//---------------------------------------

use Exception as SelfEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;

use SimpleXMLElement;
use Lib\Singles\XMLValidator;


class Test extends Controller
{

    public function doExecute(): void
    {
        $test = new XMLHandler();

    }
}




class XMLHandler
{
    private SimpleXMLElement $data;
    private XMLValidator $XMLValidator;

    /**
     * Конструктор класса
     *
     * @throws SelfEx
     */
    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/file_table_mappings.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML схемы маппингов", 1);
        }
        vd($data);

        $this->data = $data;
        $this->XMLValidator = new XMLValidator();
    }
}
