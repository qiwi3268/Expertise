<?php


namespace Lib\AccessToDocument;

use Lib\Exceptions\AccessToDocument as SelfEx;
use Classes\Application\AccessToDocument\AccessToApplication;


/**
 * Предназначен для получения экземпляра класса для проверки доступа
 * пользователя к документу в зависимости от его типа
 *
 * Паттерн: <i>ServiceLocator</i>
 *
 */
class Locator
{

    /**
     * Сущность класса
     *
     */
    private static self $instance;

    private AccessToDocument $accessToDocument;


    /**
     * Констркутор класса
     *
     * Вызывается единожды
     *
     * @param string $documentType тип документа
     * @param int $documentId id документа
     * @throws SelfEx
     */
    private function __construct(string $documentType, int $documentId)
    {
        switch ($documentType) {

            case DOCUMENT_TYPE['application'] :
                $this->accessToDocument = new AccessToApplication($documentId);
                break;

            default :
                throw new SelfEx('Методу Lib\AccessToDocument\Locator::__construct не удалось распознать тип документа', 1);
        }
    }


    /**
     * Предназначен для получения сущности класса
     *
     * @param string $documentType тип документа
     * @param int $documentId id документа
     * @return static сущность класса
     * @throws SelfEx
     */
    static public function getInstance(string $documentType, int $documentId): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self($documentType, $documentId);
        }
        return self::$instance;
    }


    /**
     * Предназначен для получения экземпляра класса
     *
     * @return AccessToDocument экземпляр класса проверки доступа пользователя к документу
     */
    public function getObject(): AccessToDocument
    {
        return $this->accessToDocument;
    }
}