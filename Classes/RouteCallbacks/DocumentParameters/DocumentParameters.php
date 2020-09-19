<?php


namespace Classes\RouteCallbacks\DocumentParameters;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Classes\Exceptions\DocumentParameters as SelfEx;
use Classes\Exceptions\PregMatch as PregMatchEx;
use Lib\Singles\PrimitiveValidator;


/**
 *  Предназначен для предоставления интерфейса дочерним классам с целью объявления констант открытого документа:
 *
 * <i>CURRENT_DOCUMENT_TYPE</i>: текущий открытый тип документа<br>
 * <i>CURRENT_DOCUMENT_ID</i>: id этого документа
 *
 */
abstract class DocumentParameters
{

    protected PrimitiveValidator $primitiveValidator;


    /**
     * Конструктор класса
     *
     */
    public function __construct(){
        $this->primitiveValidator = new PrimitiveValidator();
    }


    /**
     * Предназначен для валидации принятых параметров и объявления констант
     *
     * Вспомогательный метод для дочерних классов
     *
     * @param string $clearDocumentId очищенный (через функцию clearHtmlArr) id документа
     * @param string $pattern искомый шаблон, где тип документа находится в 1 группе
     * @param string $subject входная строка
     * @throws SelfEx
     */
    protected function validateAndDefineParameters(string $clearDocumentId, string $pattern, string $subject): void
    {
        try {
            $documentType = getHandlePregMatch($pattern, $subject, false)[1];
        } catch (PregMatchEx $e) {
            throw new SelfEx("Произошла ошибка при определении типа открытого документа", 2);
        }

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
    }


    /**
     * Предназначен для объявления констант открытого документа
     *
     */
    abstract function defineDocumentParameters(): void;
}