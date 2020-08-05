<?php


class CSP{
    

    private const CPROCSP= '/opt/cprocsp/bin/amd64/cryptcp';
    
    public function __construct(){
    }
    
    
    // Предназначен для валидации встроенной подписи
    // path string : абсолютный путь к файлу в фс сервера
    //
    public function validateInternal(string $path){
        
        $cmd = sprintf('%s -verify -mca -all -errchain -verall %s 2>&1', self::CPROCSP, $path);
        
        $message = shell_exec($cmd);
        
        var_dump($cmd);
        var_dump($message);
    }
    
    private function getErrorMessage($errorCode){
    
    }
}
