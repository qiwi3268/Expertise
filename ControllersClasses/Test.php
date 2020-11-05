<?php


namespace ControllersClasses;

use Lib\ErrorTransform\ErrorTransformer;
use Lib\ErrorTransform\Handlers\ErrorExceptionHandler;
use Lib\Exceptions\File as FileEx;


class Test extends Controller
{

    public function doExecute(): void
    {
        try {
            $this->test();
        } catch (\Exception $e) {
            $str = exceptionToString($e, 'Дополнительное описание');
            vd($str);
        }
        $result = 'Uncaught Exception';
        $result = 'lala. ' . $result;
        vd($result);
    }


    public function test()
    {
        throw new FileEx('Сообщение', 12);
    }

}