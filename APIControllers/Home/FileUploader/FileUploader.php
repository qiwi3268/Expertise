<?php


namespace APIControllers\Home\FileUploader;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;
use Tables\Exceptions\Tables as TablesEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use Lib\TableMappings\TableMappingsXMLHandler;
use Lib\Singles\Logger;
use Tables\Docs\Relations\ParentDocumentLinkerFacade;


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
     * @throws DataBaseEx
     * @throws TablesEx
     */
    public function doExecute(): void
    {

        // Проверка параметров, которые общие для всех типов загрузчиков
        list(
            'id_document'     => $documentId,
            'mapping_level_1' => $ml_1,
            'mapping_level_2' => $ml_2
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['id_document', 'mapping_level_1', 'mapping_level_2']);


        // Получение загрузчика
        try {

            $XMLHandler = new TableMappingsXMLHandler();
            $level_2 = $XMLHandler->getLevel2($ml_1, $ml_2);

            list(
                'file_table_class'    => $fileTableClass,
                'main_document_type'  => $mainDocumentType,
                'file_uploader_class' => $uploaderClass
                ) = $XMLHandler->validateLevel2Structure($level_2)->getHandledLevel2Value($level_2);
        } catch (TableMappingsEx $e) {

            $this->logAndExceptionExit(1, $e, 'Ошибка при обработке XML схемы table_mappings');
        } catch (XMLValidatorEx $e) {

            $this->logAndExceptionExit(2, $e, 'Ошибка при валидации XML схемы table_mappings');
        }

        $applicationId = (new ParentDocumentLinkerFacade($mainDocumentType, $documentId))->getApplicationId();

        $uploader = new $uploaderClass($applicationId, $documentId, $fileTableClass);

        $requiredParams = $uploader->getRequiredParams();

        if (!empty($requiredParams)) {
            $this->checkRequiredParams(HttpRequest::POST, $requiredParams);
        }

        try {

            $uploader->preparatoryCheck();
        } catch (FileEx $e) {

            if (in_array($e->getCode(), [1001, 1002])) {
                $this->logAndExceptionExit(3, $e, 'Ошибка при подготовительной проверке перед загрузкой файлов');
            } else {
                $this->exceptionExit(3, $e);
            }
        }

        try {
            $this->successExit(['uploaded_files' => $uploader->upload()]);
        } catch (FileEx $e) {
            $this->logAndExceptionExit(4, $e);
        }


        // остановился на том, что нужно получать в пост параметре обязательно id_document
        // т.е. в js надо написать фукнцию, которая 1 - парсит урл и ищет там или в инпут хиддене

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