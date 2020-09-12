<?php


use Lib\Exceptions\Actions as ActionsEx;
use Lib\Actions\Locator;


// API предназначен для выполнения действий
//
// API result:
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
// --------------------------------------------------------
//  8  - Ошибка при определении типа документа
//       {result, error_message : текст ошибки}
//  9  - Отсутствует доступ к выполняемому действию
//       {result, error_message : текст ошибки}
//  10 - Ошибка при получении проверенного результата callback'а
//       {result, message : текст ошибки, code: код ошибки}
//  11 - Ошибка при получении проверенного метода действия
//       {result, message : текст ошибки, code: код ошибки}
//  12 - todo ошибка при выполнении действия
//  13 - Все операции прошли усешно
//       {result, ref : ссылка на страницу, на которую необходимо перенаправить пользователя}
//  14 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}
//


try {

    try {

        $actions = Locator::getInstance(CURRENT_DOCUMENT_TYPE)->getActions();
    } catch (ActionsEx $e) {

        exit(json_encode([
            'result'        => 8,
            'error_message' => 'Ошибка при определении типа документа'
        ]));
    }

    // Проверка доступа к действию
    try {

        if (!$actions->getAccessActions()->checkAccessFromActionByPageName(CURRENT_PAGE_NAME)) {

            exit(json_encode([
                'result'        => 9,
                'error_message' => 'Отсутствует доступ к выполняемому действию'
            ]));
        }
    } catch (ActionsEx $e) {

        // Ошибка при получении проверенного результата callback'а
        exit(json_encode([
            'result'  => 10,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }

    try {

        $ref = $actions->getExecutionActions()->executeCallbackByPageName(CURRENT_PAGE_NAME);
    } catch (ActionsEx $e) {

        // Ошибка при получении проверенного метода действия
        exit(json_encode([
            'result'  => 11,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));

        //todo ошибка при выполнении действия
    }

    exit(json_encode([
        'result'  => 13,
        'ref'     => $ref,
    ]));

} catch (Exception $e) {

    exit(json_encode([
        'result'  => 14,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}