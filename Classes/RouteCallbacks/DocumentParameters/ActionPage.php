<?php


namespace Classes\RouteCallbacks\DocumentParameters;
use Classes\Exceptions\DocumentParameters as SelfEx;


/**
 *  Предназначен для объявления констант открытого документа из страницы открытого действия
 *
 */
class ActionPage extends DocumentParameters
{

    /**
     * Реализация абстрактного метода
     *
     * @throws SelfEx
     */
    public function defineDocumentParameters(): void
    {
        if (!checkParamsGET('id_document')) {
            throw new SelfEx("id открытого документа не существует в GET параметрах", 1);
        }

        $clearDocumentId = clearHtmlArr($_GET)['id_document'];

        // начало текста
        // home/
        // 1 группа:
        //   любой символ латиницы один и более раз
        // /actions/action_
        // любая цифра один и более раз
        // конец текста
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = "/\Ahome\/([a-z]+)\/actions\/action_\d+\z/iu";

        $this->validateAndDefineParameters($clearDocumentId, $pattern, URN);
    }
}