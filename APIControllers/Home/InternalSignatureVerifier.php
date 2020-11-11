<?php


namespace APIControllers\Home;


use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;

use Lib\Singles\Logger;



/**
 * API предназначен для
 *
 * API result:

 *
 */
class InternalSignatureVerifier extends APIController
{

    /**
     * Реализация абстрактного метода
     *
     */
    public function doExecute(): void
    {

    }

    /**
     * Реализация абстрактного метода
     *
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS, 'InternalSignatureVerifier.log');
    }
}