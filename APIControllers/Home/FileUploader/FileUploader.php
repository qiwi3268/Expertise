<?php


namespace APIControllers\Home\FileUploader;

use core\Classes\ControllersInterface\APIController;
use Lib\Singles\Logger;

/**
 * API предназначен для загрузке файлов в контексте документа
 *
 * API result:
 * - ok -
 *
 */
class FileUploader extends APIController
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
        return new Logger(LOGS_API_ERRORS, 'FileUploader.log');
    }
}