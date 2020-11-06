<?php


namespace APIControllers;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase;
use Lib\Exceptions\DataBase as DataBaseEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use core\Classes\Session;
use Lib\Singles\Logger;
use Tables\user;


/**
 * API предназначен для проверки возможности выгрузить указанный файл в констекте документа
 *
 * API result:
 *
 */
class FileChecker extends APIController
{

    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
     */
    public function doExecute(): void
    {
        list(
            'id_file'         => $id_file,
            'mapping_level_1' => $mapping_level_1,
            'mapping_level_2' => $mapping_level_2
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['id_file', 'mapping_level_1', 'mapping_level_2']);

    }

    //определяю по маппингу тип документа
    //по нему определяю id заявления
    //по нему директорию и дальше по плану


    /**
     * Реализация абстрактного метода
     *
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS, 'FileChecker.log');
    }
}