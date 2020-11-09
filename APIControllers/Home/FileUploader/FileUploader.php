<?php


namespace APIControllers\Home\FileUploader;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use Lib\TableMappings\TableMappingsXMLHandler;
use Lib\Singles\Logger;



/**
 * API предназначен для загрузке файлов в контексте документа
 *
 * API result:
 * - ok -
 * - 1 - Ошибка при обработке XML схемы table_mappings
 * - 2 - Ошибка при валидации XML схемы table_mappings
 *
 *
 */
class FileUploader extends APIController
{


    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
     */
    public function doExecute(): void
    {

        // Проверка параметров, которые общие для всех типов загрузчиков
        list(
            'mapping_level_1' => $ml_1,
            'mapping_level_2' => $ml_2
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['mapping_level_1', 'mapping_level_2']);


        // Получение загрузчика
        try {

            $XMLHandler = new TableMappingsXMLHandler();
            $level_2 = $XMLHandler->getLevel2($ml_1, $ml_2);

            list(
                'file_table_class'    => $fileTable,
                'file_uploader_class' => $uploaderClass
                ) = $XMLHandler->validateLevel2Structure($level_2)->getHandledLevel2Value($level_2);
        } catch (TableMappingsEx $e) {

            $this->logAndExceptionExit(1, $e, 'Ошибка при обработке XML схемы table_mappings');
        } catch (XMLValidatorEx $e) {

            $this->logAndExceptionExit(2, $e, 'Ошибка при валидации XML схемы table_mappings');
        }

        $uploader = new $uploaderClass();

        $requiredParams = $uploader->getRequiredParams();

        if (!empty($requiredParams)) {
            $this->checkRequiredParams(HttpRequest::POST, $requiredParams);
        }

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