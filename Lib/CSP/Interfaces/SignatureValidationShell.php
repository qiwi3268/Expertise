<?php


namespace Lib\CSP\Interfaces;


/**
 * Предоставляет интерфейс для классов, которым необходимо реализовать методы получения результата валидации подписи
 *
 * <b>С</b> проверкой цепочки сертификатов и <b>БЕЗ</b> проверки цепочки сертификатов
 *
 */
interface SignatureValidationShell
{

    /**
     * Предназначен для получения результата валидации подписи <b>С</b> проверкой цепочки сертификатов
     *
     * @param array $filePaths
     * @return string
     */
    public function execErrChain(array $filePaths): string;


    /**
     * Предназначен для получения результата валидации подписи <b>БЕЗ</b> проверкой цепочки сертификатов
     *
     * @param array $filePaths
     * @return string
     */
    public function execNoChain(array $filePaths): string;
}