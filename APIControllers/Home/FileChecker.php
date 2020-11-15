<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;
use Lib\Exceptions\Logger as LoggerEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use Lib\TableMappings\TableMappingsXMLHandler;
use Lib\Singles\Logger;
use Tables\Docs\Relations\ParentDocumentLinkerFacade;


/**
 * API предназначен для проверки возможности выгрузить указанный файл в констекте документа
 *
 * API result:
 * - ok - ['fs_name', 'file_name']
 * - 1  - Ошибка при обработке XML схемы table_mappings
 * - 2  - Ошибка при валидации XML схемы table_mappings
 * - 3  - Запрашиваемая запись файла не существует в БД
 * - 4  - У запрашиваемой записи файла в БД не проставлен флаг загрузки на сервер
 * - 5  - Файл физически отсутствует на сервере
 *
 */
class FileChecker extends APIController
{

    /**
     * Реализация абстрактного метода
     *
     * @throws DataBaseEx
     * @throws RequestEx
     * @throws TablesEx
     */
    public function doExecute(): void
    {
        list(
            'id_file'         => $id_file,
            'mapping_level_1' => $ml_1,
            'mapping_level_2' => $ml_2
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['id_file', 'mapping_level_1', 'mapping_level_2']);

        try {

            $XMLHandler = new TableMappingsXMLHandler();
            $level_2 = $XMLHandler->getLevel2($ml_1, $ml_2);

            list(
                'file_table_class'   => $fileTable,
                'main_document_type' => $mainDocumentType
                ) = $XMLHandler->validateLevel2Structure($level_2)->getHandledLevel2Value($level_2);
        } catch (TableMappingsEx $e) {

            $this->logAndExceptionExit(1, $e, 'Ошибка при обработке XML схемы table_mappings');
        } catch (XMLValidatorEx $e) {

            $this->logAndExceptionExit(2, $e, 'Ошибка при валидации XML схемы table_mappings');
        }

        $fileAssoc = $fileTable::getAssocById($id_file);

        // Проверка на существование записи в таблице
        if (is_null($fileAssoc)) {
            $this->logAndErrorExit(3, 'Запрашиваемая запись файла не существует в БД');
        }

        // Проверка на успешную загрузку файла на сервер
        if ($fileAssoc['is_uploaded'] == 0) {
            $this->logAndErrorExit(4, 'У запрашиваемой записи файла в БД не проставлен флаг загрузки на сервер');
        }

        $applicationId = (new ParentDocumentLinkerFacade($mainDocumentType, $fileAssoc['id_main_document']))->getApplicationId();

        $filePath = APPLICATIONS_FILES . "/{$applicationId}/{$fileAssoc['hash']}";

        // Проверка файла на физическое существование
        if (!file_exists($filePath)) {
            $this->logAndErrorExit(5, 'Файл физически отсутствует на сервере');
        }

        // Все прошло успешно
        $this->successExit([
            'fs_name'   => $filePath,
            'file_name' => $fileAssoc['file_name']
        ]);
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws LoggerEx
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS . '/FileChecker.log');
    }
}