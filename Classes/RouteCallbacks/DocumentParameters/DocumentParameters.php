<?php


namespace Classes\RouteCallbacks\DocumentParameters;

use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Classes\Exceptions\DocumentParameters as SelfEx;
use functions\Exceptions\Functions as FunctionsEx;

use core\Classes\Request\HttpRequest;
use Lib\Singles\PrimitiveValidator;


/**
 * Предназначен для предоставления интерфейса дочерним классам с целью объявления констант открытого документа:
 *
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 *
 */
abstract class DocumentParameters
{

    protected HttpRequest $request;
    protected PrimitiveValidator $primitiveValidator;


    /**
     * Конструктор класса
     *
     */
    public function __construct()
    {
        $this->request = HttpRequest::getInstance();
        $this->primitiveValidator = new PrimitiveValidator();
    }


    /**
     * Предназначен для валидации принятых параметров и объявления констант
     *
     * Вспомогательный метод для дочерних классов
     *
     * @param string $clearDocumentId очищенный (через функцию clearHtmlArr) id документа
     * @param string $pattern искомый шаблон
     * @param int $documentTypeIndex индекс из результата вызова функции {@see getHandlePregMatch()},
     * в котором находится тип документа
     * @param string $subject входная строка
     * @return array результат вызова функции {@see getHandlePregMatch()}
     * @throws SelfEx
     */
    protected function validateAndDefineParameters(
        string $clearDocumentId,
        string $pattern,
        int $documentTypeIndex,
        string $subject
    ): array {

        try {
            $result = getHandlePregMatch($pattern, $subject, false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Произошла ошибка при определении типа открытого документа", 2);
        }

        $documentType = $result[$documentTypeIndex];

        if (!isset(DOCUMENT_TYPE[$documentType])) {
            throw new SelfEx("Открытый тип документа не определен в константе DOCUMENT_TYPE", 3);
        }

        try {
            $this->primitiveValidator->validateInt($clearDocumentId);
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("id открытого документа не является целочисленным значением", 4);
        }

        define('CURRENT_DOCUMENT_TYPE', $documentType);
        define('CURRENT_DOCUMENT_ID', (int)$clearDocumentId);

        return $result;
    }


    /**
     * Предназначен для объявления констант открытого документа
     *
     */
    abstract function defineDocumentParameters(): void;
}