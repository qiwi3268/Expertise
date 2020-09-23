<?php


namespace Lib\AccessToDocument;

use Lib\Exceptions\AccessToDocument as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Предназначен для проверки доступа пользователя к документу
 *
 * Используется при формировании сайдбара иерархии и при непосредственном
 * переходе по карточкам экспертизы
 *
 */
abstract class AccessToDocument
{

    /**
     * id документа, для которого определяется доступ
     *
     */
    protected int $documentId;


    /**
     * Конструктор класса
     *
     * @param int $documentId
     */
    public function __construct(int $documentId)
    {
        $this->documentId = $documentId;
    }


    /**
     * Предназначен для проверки доступа пользователя к документу
     *
     * <b>**</b> Проверка доступа должна производиться только к нужному документу,
     * а не ко всему дереву наследования
     *
     * @throws SelfEx
     * @throws DataBaseEx
     */
    abstract public function checkAccess(): void;
}