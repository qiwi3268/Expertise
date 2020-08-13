<?php


namespace csp;


abstract class SignatureValidationShell{
    
    
    // Предназначен для получения результата валидации подписи С проверкой цепочки сертификатов
    //
    abstract public function execErrChain(array $filePaths):string;
    
    
    // Предназначен для получения результата валидации подписи БЕЗ проверкой цепочки сертификатов
    //
    abstract public function execNoChain(array $filePaths):string;
}