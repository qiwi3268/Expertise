<?php


namespace Classes\DocumentParameters;
use Classes\Exceptions\DocumentParameters as SelfEx;


// Предназначен для объявления констант открытого документа из API выполнений действий
//
class APIActionExecutor extends DocumentParameters
{

    // Предназначен для объявления констант открытого документа
    // Выбрасывает исключения--------------------------------
    // Classes\Exceptions\DocumentParameters :
    // code:
    //  1  - id открытого документа и/или path_name не существует в POST параметрах
    //
    public function defineDocumentParameters(): void
    {
        if (!checkParamsPOST('path_name', 'id_document')) {
            throw new SelfEx("id открытого документа и/или path_name не существует в POST параметрах", 1);
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

        $this->validateAndDefineParameters($clearDocumentId, $pattern, $path_name);
    }
}