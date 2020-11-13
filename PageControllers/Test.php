<?php


namespace PageControllers;

use APIControllers\Home\FileUploader\Types\Type1;
use core\Classes\ControllersInterface\PageController;

use APIControllers\Home\FileNeedsSetter;
use core\Classes\ControllersInterface\APIController;
use core\Classes\Request\HttpRequest;
use core\Classes\Request\Request;
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


use RuntimeException;
use Lib\Logger\Writer;


use RecursiveDirectoryIterator;
use RecursiveCallbackFilterIterator;
use RecursiveIteratorIterator;
use SplFileObject;


class Test extends PageController
{

    public function doExecute(): void
    {
        $path = LOGS . "/lala.csv";
        $logger = new Writer($path);
        //$logger->write("Пр \r\n ивет23", false);

        $reader = new SplFileObject($path);
        $reader->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::READ_CSV);
        $reader->setCsvControl(Writer::CSV_DELIMITER, Writer::CSV_ENCLOSURE, Writer::CSV_ESCAPE_CHAR);

        foreach ($reader as $line) {
            vd($line);
        }
    }
}


class LogReader extends SplFileObject
{

    public function __construct($file_name)
    {
        parent::__construct($file_name, 'r', false, null);

        $this->setFlags(SplFileObject::DROP_NEW_LINE);
        //            $file->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
    }
}


class test1
{
    public function test(): void
    {
        $directory = new RecursiveDirectoryIterator(LOGS);

        $files = new RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator): bool
        {
            // Разрешить рекурсию
            if ($iterator->hasChildren()) {
                return true;
            }

            // Не пустые файлы логов ошибок
            if (
                $current->isFile()
                && contains($key, 'errors')
                && $current->getSize() > 0
            ) {
                return true;
            }
            return false;
        });


        foreach (new RecursiveIteratorIterator($files) as $key => $file) {


            $logFile = new LogReader($key);


            vd($file->getPathname());
            foreach ($logFile as $line) {
                //vd($line);

                list (1 => $date, 2 => $msg) = getHandlePregMatch("/(.+)\s\|\s(.+)/", $line, false);
                vd($date);
            }


        }
    }
}