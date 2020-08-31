<?php


namespace Lib\CSP\Interfaces;


interface SignatureValidationShell
{


    // Предназначен для получения результата валидации подписи С проверкой цепочки сертификатов
    //
    public function execErrChain(array $filePaths): string;


    // Предназначен для получения результата валидации подписи БЕЗ проверкой цепочки сертификатов
    //
    public function execNoChain(array $filePaths): string;
}