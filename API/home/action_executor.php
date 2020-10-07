<?php


use Lib\Exceptions\Actions as ActionsEx;
use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;

use core\Classes\Session;
use Lib\Actions\Locator;
use Lib\AccessToDocument\AccessToDocumentTree;
use Tables\Locators\DocumentTypeTableLocator;

// API предназначен для выполнения действий
//
// API result:
// Classes\RouteCallbacks\DocumentParameters\APIActionExecutor----------
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//	2  - Произошла ошибка при определении типа открытого документа
//       {result, error_message : текст ошибки}
//	3  - Открытый тип документа не определен в константе DOCUMENT_TYPE
//       {result, error_message : текст ошибки}
//	4  - id открытого документа не является целочисленным значением
//       {result, error_message : текст ошибки}
//	5  - Неизвестная ошибка Classes\Exceptions\DocumentParameters
//       {result, error_message : текст ошибки}
//	6  - Произошла ошибка при определении CURRENT_PAGE_NAME
//       {result, error_message : текст ошибки}
//  7  - Непредвиденная ошибка метода Classes\DocumentParameters\APIActionExecutor::defineDocumentParameters
//       {result, message : текст ошибки, code: код ошибки}
// ---------------------------------------------------------------------
//  8  - Для работы с действиями Вам необходимо авторизоваться
//       {result, error_message : текст ошибки}
//  9  - Ошибка при распознании типа документа
//       {result, error_message : текст ошибки}
//  10 - Запрашиваемый документ не существует
//       {result, error_message : текст ошибки}
//  11 - Ошибка при проверке доступа к текущему документу (вероятно, отсутствует доступ к текущему документу)
//       {result, message : текст ошибки, code: код ошибки}
//  12 - Отсутствует доступ к выполняемому действию
//       {result, error_message : текст ошибки}
//  13 - Ошибка при получении проверенного результата callback'а
//       {result, message : текст ошибки, code: код ошибки}
//  14 - Ошибка при получении проверенного метода действия
//       {result, error_message : текст ошибки}
//  15 - Нет обязательного параметра POST / GET запроса
//       {result, error_message : текст ошибки}
//  16 - Ошибка во время исполнения действия
//       {result, error_message : текст ошибки}
//  17 - Неизвестная ошибка во время работы с классом действий
//       {result, message : текст ошибки, code: код ошибки}
//  18 - Все прошло успешно
//       {result, ref : ссылка на страницу, на которую необходимо перенаправить пользователя}
//  19 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}
//

try {

    if (!Session::isAuthorized()) {

        exit(json_encode([
            'result'        => 8,
            'error_message' => 'Для работы с действиями Вам необходимо авторизоваться'
        ]));
    }

    try {

        $actions = Locator::getInstance(CURRENT_DOCUMENT_TYPE)->getObject();
    } catch (ActionsEx $e) {

        exit(json_encode([
            'result'        => 9,
            'error_message' => 'Ошибка при распознании типа документа'
        ]));
    }

    // Проверка существования текущего документа
    $tableLocator = new DocumentTypeTableLocator(CURRENT_DOCUMENT_TYPE);
    $docTable = $tableLocator->getDocs();

    if (!$docTable::checkExistById(CURRENT_DOCUMENT_ID)) {

        exit(json_encode([
            'result'        => 10,
            'error_message' => 'Запрашиваемый документ documentType: ' . CURRENT_DOCUMENT_TYPE . ' id: ' . CURRENT_DOCUMENT_ID . ' не существует'
        ]));
    }

    // Проверка доступа к текущему докменту с учетом всего дерева наследования
    try {

        $accessToDocumentTree = new AccessToDocumentTree(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
        $accessToDocumentTree->checkAccessToDocumentTree();
    } catch (AccessToDocumentEx $e) {

        exit(json_encode([
            'result'  => 11,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }


    // Проверка доступа к действию
    try {

        if (!$actions->getAccessActions()->checkAccessFromActionByPageName(CURRENT_PAGE_NAME)) {

            exit(json_encode([
                'result'        => 12,
                'error_message' => 'Отсутствует доступ к выполняемому действию'
            ]));
        }
    } catch (ActionsEx $e) {

        // Ошибка при получении проверенного результата callback'а
        exit(json_encode([
            'result'  => 13,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }

    try {

        $ref = $actions->getExecutionActions()->executeCallbackByPageName(CURRENT_PAGE_NAME);
    } catch (ActionsEx $e) {

        $e_message = $e->getMessage();
        $e_code = $e->getCode();

        // Ошибка при получении проверенного метода действия
        switch ($e_code) {
            case 2 : // попытка получить доступ к несуществующему действию для страницы
            case 3 : // метод исполнения действия не реализован в дочернем классе
            case 4 : // ошибка метода исполнения действия для страницы
                exit(json_encode([
                    'result'        => 14,
                    'error_message' => $e->getMessage(),
                ]));

            case 5 : // Нет обязательного параметра POST / GET запроса
                exit(json_encode([
                    'result'        => 15,
                    'error_message' => $e->getMessage(),
                ]));

            case 6 : // Ошибка во время исполнения действия
                exit(json_encode([
                    'result'        => 16,
                    'error_message' => $e->getMessage(),
                ]));

            default : // Неизвестная ошибка во время работы с классом действий
                exit(json_encode([
                    'result'  => 17,
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode()
                ]));
        }
    }

    // Получение id действия
    $actionId = call_user_func([$actions->getActionTable(), 'getIdByPageName'], CURRENT_PAGE_NAME);

    $historyTable = $tableLocator->getActionsHistory();



    // Все прошло успешно
    exit(json_encode([
        'result'  => 18,
        'ref'     => $ref,
    ]));

} catch (Exception $e) {

    exit(json_encode([
        'result'  => 19,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}