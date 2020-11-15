<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use core\Classes\Session;
use Lib\Exceptions\TableMappings as TableMappingsEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\Logger as LoggerEx;
use Lib\Exceptions\URIParser as URIParserEx;
use Lib\Exceptions\Actions as ActionsEx;
use ReflectionException;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Tables\Exceptions\Tables as TablesEx;
use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use Lib\Actions\Locator;
use Lib\DataBase\Transaction;
use Lib\Singles\Logger;
use Lib\Singles\PrimitiveValidator;
use Classes\RouteCallbacks\DocumentParameters\APIActionExecutor;
use Tables\Locators\DocumentTypeTableLocator;
use Lib\AccessToDocument\AccessToDocumentTree;

//todo use
/**
 * API предназначен
 *
 * API result:
 * 1 - Ошибка при объявлении констант документа
 * 2 - Отсутствует авторизация на сервере
 * 3 - Ошибка при распознании типа документа
 * 4 - Запрашиваемый документ не существует
 *
 */
class ActionExecutor extends APIController
{
    private const TODO_RESULT_1 = '11';
    private const TODO_RESULT_2 = '11';



    /**
     * Реализация абстрактного метода
     *
     * @throws DataBaseEx
     * @throws RequestEx
     * @throws DocumentTreeHandlerEx
     * @throws TablesEx
     * @throws ReflectionException
     */
    public function doExecute(): void
    {
        $URI = $this->getCheckedRequiredParams(HttpRequest::POST, ['uri'])['uri'];

        try {
            (new APIActionExecutor($URI))->defineDocumentParameters();
        } catch (URIParserEx $e) {
            $this->logAndExceptionExit(1, $e, 'Ошибка при объявлении констант документа');
        }

        if (!Session::isAuthorized()) {
            $this->errorExit(2, 'Отсутствует авторизация на сервере');
        }

        try {
            $actions = Locator::getInstance(CURRENT_DOCUMENT_TYPE)->getObject();
        } catch (ActionsEx $e) {
            $this->logAndExceptionExit(3, $e, 'Ошибка при распознании типа документа');
        }

        // Проверка существования текущего документа
        $tableLocator = new DocumentTypeTableLocator(CURRENT_DOCUMENT_TYPE);
        $docTable = $tableLocator->getDocs();

        if (!$docTable::checkExistById(CURRENT_DOCUMENT_ID)) {
            $this->logAndErrorExit(4, 'Запрашиваемый документ documentType: ' . CURRENT_DOCUMENT_TYPE . ' id: ' . CURRENT_DOCUMENT_ID . ' не существует');
        }

        // Проверка доступа к текущему документу с учетом всего дерева наследования
        try {
            $accessToDocumentTree = new AccessToDocumentTree(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
            $accessToDocumentTree->checkAccessToDocumentTree();
        } catch (AccessToDocumentEx $e) {

            $e_code = $e->getCode();

            // Коды технических ошибок
            if ($e_code == 1001 || $e_code == 2001) {
                $this->logAndExceptionExit(5, $e, 'Ошибка при проверке доступа к текущему документу');
            }
            $this->exceptionExit(self::TODO_RESULT_1, $e);
        }

        // Проверка доступа к действию
        try {
            if (!$actions->getAccessActions()->checkAccessFromActionByPageName(CURRENT_PAGE_NAME)) {
                $this->errorExit(self::TODO_RESULT_2, 'Отсутствует доступ в выполняемому действию');
            }
        } catch (ActionsEx $e) {
            $this->logAndExceptionExit(6, $e, "Ошибка при получении проверенного результата callback'а");
        }


        try {
            $executionActionsResult = $actions->getExecutionActions()->executeCallbackByPageName(CURRENT_PAGE_NAME);
        } catch (ActionsEx $e) {
            //todo
            $code1 = 1;
            $code2 = 2;
            $e_code = $e->getCode();

        }



    }


    /**
     * Реализация абстрактного метода
     *
     * @throws LoggerEx
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS . '/ActionExecutor.log');
    }
}