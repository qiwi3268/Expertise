<?php


namespace APIControllers\Home\FileUploader;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;
use Lib\Exceptions\URIParser as URIParserEx;
use Tables\Exceptions\Tables as TablesEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use Lib\TableMappings\TableMappingsXMLHandler;
use Lib\Singles\Logger;
use Lib\Singles\URIParser;
use Tables\Docs\Relations\ParentDocumentLinkerFacade;


/**
 * API предназначен для загрузке файлов в контексте документа
 *
 * API result:
 * - ok   - [['id', 'name', 'hash', 'human_file_size'], ...]
 * - uepc - Ошибки со стороны пользователя при подготовительной проверке (загружены некорреткные файлы)
 * - 1    - Ошибка при обработке XML схемы table_mappings
 * - 2    - Ошибка при валидации XML схемы table_mappings
 * - 3    - Ошибка при разборе входного URI
 * - 4    - При различных типах документа входной параметр 'target_document_id' не может быть null
 * - 5    - Ошибка при подготовительной проверке перед загрузкой файлов
 * - 6    - Ошибка при создании записей в файловую таблицу / при переносе загруженного файла / при обновлении флагов загрузки файла
 *
 */
class FileUploader extends APIController
{

    /**
     * Код выхода, соответствующий ошибкам со стороны пользователя при подготовительной проверке
     *
     * - файлы не прошли проверки на допустимые форматы
     * - файлы не прошли проверки на запрещенные символы
     * - файлы не прошли проверки на максимально допустимый размер
     *
     */
    private const USER_ERROR_PREPARATORY_CHECK = 'uepc';


    /**
     * Реализация абстрактного метода
     *
     * <p>target_document_id <b>int</b>:</p>
     * - загружаются файлы в другой тип документа (не равный полученному из uri)
     * - загружаются файлы в такой-же тип документа, но другой id
     * <p>target_document <b>null</b>:</p>
     * - загружаются файлы в этот же документ (т.е. равен и тип документа и id)
     * @throws RequestEx
     * @throws DataBaseEx
     * @throws TablesEx
     */
    public function doExecute(): void
    {
        // Целевой тип документа - берется из XML схемы table_mappings согласно принятым маппингам
        // Целевой id документа  - берется из входного параметра target_document_id

        // Проверка обязательных параметров, общих для всех типов загрузчиков
        list(
            'uri'                => $URI,
            'target_document_id' => $targetDocumentId,
            'mapping_level_1'    => $ml_1,
            'mapping_level_2'    => $ml_2
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['uri', 'target_document_id', 'mapping_level_1', 'mapping_level_2']);

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

        // Определение параметров страницы, с которой был сделан запрос
        try {

            list(
                'document_type' => $req_documentType,
                'document_id'   => $req_documentId
                ) = URIParser::parse($URI);
        } catch (URIParserEx $e) {

            $this->logAndExceptionExit(3, $e, 'Ошибка при разборе входного URI');
        }

        // Входной параметр 'target_document_id' обязателен, если загружаются файлы из одного документа в другой
        if (is_null($targetDocumentId)) {

            if ($mainDocumentType != $req_documentType) {

                $this->logAndErrorExit(4, "При различных типах документа входной параметр 'target_document_id' не может быть null");
            }
            $targetDocumentId = $req_documentId;
        }

        // id заявления для страницы, с которой был сделан запрос
        $req_applicationId = (new ParentDocumentLinkerFacade($req_documentType, $req_documentId))->getApplicationId();

        $dir = APPLICATIONS_FILES . "/{$req_applicationId}";

        $uploader = new $uploaderClass($dir, $targetDocumentId, $fileTableClass);

        $requiredParams = $uploader->getRequiredParams();

        // Проверка обязательных параметров, требуемых для текущего загрузчика
        if (!empty($requiredParams)) {
            $this->checkRequiredParams(HttpRequest::POST, $requiredParams);
        }

        try {

            // Подготовительная проверка перед загрузкой файлов
            $uploader->preparatoryCheck();
        } catch (FileEx $e) {

            $e_code = $e->getCode();

            // Коды технических ошибок
            if ($e_code == 1001 || $e_code == 1002) {

                $this->logAndExceptionExit(5, $e, 'Ошибка при подготовительной проверке перед загрузкой файлов');
            } else {

                $message = $e->getMessage();

                switch ($e_code) {

                    case 1003 :

                        $debug = implode(', ', $uploader->getAllowedFormats());
                        $message .= ". Допустимые форматы файлов: '{$debug}'";
                        break;
                    case 1004 :

                        $debug = implode(', ', $uploader->getForbiddenSymbols());
                        $message .= ". Запрещенные символы: '{$debug}'";
                        break;
                    case 1005 :

                        $size = $uploader->getMaxFileSize();
                        $message .= ". Максимально допустимый размер файла: {$size} Мб";
                        break;
                }
                $this->errorExit(self::USER_ERROR_PREPARATORY_CHECK, $message);
            }
        }

        try {

            // Загрузка файлов и добавления записей в БД
            $this->successExit(['uploaded_files' => $uploader->upload()]);
        } catch (FileEx $e) {
            $this->logAndExceptionExit(6, $e);
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