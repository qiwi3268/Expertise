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
        $params = ['a' => 1, 'b' => 2];
        $required = ['a', 'c', 'd'];

        // empty []

        $test = array_diff($required, array_keys($params));

        vd($test);


        /*$a = new test1();
        $a->execute();

        $b = new test2();
        $b->execute();

        $c = new test3();
        $c->execute();*/
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
        vd('test1');
        //$this->exit(parent::SUCCESS_RESULT, 'test1');
    }
}

class test2 extends APIController
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
        vd('test2');
        //$this->exit(parent::SUCCESS_RESULT, 'test2');
    }
}

class test3 extends APIController
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
        vd('test3');
      //$this->exit(parent::SUCCESS_RESULT, 'test3');
    }
}