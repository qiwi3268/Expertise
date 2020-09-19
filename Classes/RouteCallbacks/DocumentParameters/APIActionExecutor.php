<?php


namespace Classes\RouteCallbacks\DocumentParameters;

use Classes\Exceptions\DocumentParameters as SelfEx;
use Classes\Exceptions\PregMatch as PregMatchEx;
use Exception;


/**
 *  Предназначен для объявления констант открытого документа из API выполнений действий
 *
 */
class APIActionExecutor extends DocumentParameters
{

    /**
     * Реализация абстрактного метода
     *
     * <b>***</b> Выполняется в контексте API_action_executor, в связи с чем используется exit<br>
     * Дополнительно объявляет константу CURRENT_PAGE_NAME для работы API_action_executor
     *
     */
    public function defineDocumentParameters(): void
    {
        try {

            if (!checkParamsPOST('path_name', 'id_document')) {
                exit(json_encode([
                    'result'        => 1,
                    'error_message' => 'Нет обязательных параметров POST запроса'
                ]));
            }

            list('path_name' => $path_name, 'id_document' => $clearDocumentId) = clearHtmlArr($_POST);

            // начало текста
            // /home/
            // 1 группа:
            //   любой символ латиницы один и более раз
            // /actions/action_
            // любая цифра один и более раз
            // конец текста
            // - регистронезависимые
            // - использование кодировки utf-8
            $pattern = "/\A\/home\/([a-z]+)\/actions\/action_\d+\z/iu";

            try {

                $this->validateAndDefineParameters($clearDocumentId, $pattern, $path_name);
            } catch (SelfEx $e) {

                $e_message = $e->getMessage();
                $e_code = $e->getCode();

                switch ($e_code) {
                    case 2 :
                        // Произошла ошибка при определении типа открытого документа
                        exit(json_encode([
                            'result'        => 2,
                            'error_message' => $e_message
                        ]));
                    case 3 :
                        // Открытый тип документа не определен в константе DOCUMENT_TYPE
                        exit(json_encode([
                            'result'        => 3,
                            'error_message' => $e_message
                        ]));
                    case 4 :
                        // id открытого документа не является целочисленным значением
                        exit(json_encode([
                            'result'        => 4,
                            'error_message' => $e_message
                        ]));
                    default :
                        exit(json_encode([
                            'result'        => 5,
                            'error_message' => "Неизвестная ошибка Classes\Exceptions\DocumentParameters. message: '{$e_message}', code: '{$e_code}'"
                        ]));
                }
            }

            try {

                define(
                    'CURRENT_PAGE_NAME',
                    getHandlePregMatch("/\A\/(.+)\z/", $path_name, false)[1]
                );
            } catch (PregMatchEx $e) {

                exit(json_encode([
                    'result'        => 6,
                    'error_message' => 'Произошла ошибка при определении CURRENT_PAGE_NAME'
                ]));
            }
        } catch (Exception $e) {

            exit(json_encode([
                'result'  => 7,
                'message' => $e->getMessage(),
                'code'    => $e->getCode()
            ]));
        }
    }
}