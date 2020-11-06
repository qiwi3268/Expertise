<?php


namespace ControllersClasses;

use core\Classes\ControllersInterface\APIController;
use Lib\ErrorTransform\ErrorTransformer;
use Lib\ErrorTransform\Handlers\ErrorExceptionHandler;
use Lib\Exceptions\DataBase;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;
use Lib\Singles\Logger;


class Test extends Controller
{

    public function doExecute(): void
    {
        $a = new test1();
        $a->execute();
    }


}


class test1 extends APIController
{

    /**
     * @inheritDoc
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(ROOT . '/controllers/tmp', 'test.log');
    }

    /**
     * @inheritDoc
     */
    public function doExecute(): void
    {
        $this->customExit(parent::SUCCESS_RESULT, [
            'key1' => 11,
            'key2' => 12
        ]);
    }
}