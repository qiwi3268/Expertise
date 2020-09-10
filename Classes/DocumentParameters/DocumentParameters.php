<?php

namespace Classes\DocumentParameters;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Classes\Exceptions\DocumentParameters as SelfEx;
use Classes\Exceptions\PregMatch as PregMatchEx;
use Lib\Singles\PrimitiveValidator;


// Предназначен для предоставления интерфейса дочерним классам с целью объявления констант открытого документа:
// CURRENT_DOCUMENT_TYPE : текущий открытый тип документа
// CURRENT_DOCUMENT_ID : id этого документа
//
abstract class DocumentParameters
{

    protected PrimitiveValidator $primitiveValidator;


    public function __construct(){
        $this->primitiveValidator = new PrimitiveValidator();
    }


    // Вспомогательный метод для дочерних классов
    // Предназначен для валидации принятых параметров и объявления констант
    // Принимает параметры-----------------------------------
    // clearDocumentId string : очищенный (через функцию clearHtmlArr) id документа
    // pattern         string : искомый шаблон
    // subject         string : входная строка
    // Выбрасывает исключения--------------------------------
    // Classes\Exceptions\DocumentParameters :
    // code:
    //  2  - произошла ошибка при определении типа открытого документа
    //  3  - открытый тип документа не определен в константе DOCUMENT_TYPE
    //  4  - id открытого документа не является целочисленным значением
    //
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

    // Предназначен для объявления констант открытого документа
    //
    abstract function defineDocumentParameters(): void;
}