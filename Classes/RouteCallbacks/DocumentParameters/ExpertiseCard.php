<?php


namespace Classes\RouteCallbacks\DocumentParameters;
use Classes\Exceptions\DocumentParameters as SelfEx;


/**
 * Предназначен для объявления констант открытого документа из карточек экспертизы:
 *
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 * - CURRENT_VIEW_MODE
 *
 */
class ExpertiseCard extends DocumentParameters
{

    /**
     * Реализация абстрактного метода
     *
     * @throws SelfEx
     */
    public function defineDocumentParameters(): void
    {
        if (!$this->request->hasInGET('id_document')) {
            throw new SelfEx("id открытого документа не существует в GET параметрах", 1);
        }

        // начало текста
        // home/expertise_cards/
        // 1 группа
        //    любой не пробельный символ один и более раз
        // /
        // 2 группа:
        //    любой не пробельный символ один и более раз
        // конец текста
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = "/\Ahome\/expertise_cards\/(\S+)\/(\S+)\z/iu";

        define(
            'CURRENT_VIEW_MODE',
            $this->validateAndDefineParameters($this->request->id_document, $pattern, 1,URN)[2]
        );
    }
}