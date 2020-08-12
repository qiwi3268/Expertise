<?php


namespace csp;


abstract class Shell{
    
    // Путь в ФС сервера к утилите cryptcp
    protected const CPROCSP = '/opt/cprocsp/bin/amd64/cryptcp';
    
    
    // Предназначен для исполнения cmd команды по валидации подписи
    // Принимает параметры-----------------------------------
    // cmd string: cmd команда
    // Возвращает параметры----------------------------------
    // string  : вывод исполняемой команды
    // Выбрасывает исключения--------------------------------
    // CSPShellException : исполняемая команда не произвела вывод или произошла ошибка
    //
    protected function exec(string $cmd):string {

        $message = shell_exec($cmd);
        
        if(is_null($message)){
            throw new \CSPShellException("Исполняемая команда: '{$cmd}' не произвела вывод или произошла ошибка");
        }
        return $message;
    }
    
    
    // Предназначен для получения результата валидации подписи С проверкой цепочки сертификатов
    //
    abstract public function execErrChain(array $filePaths):string;
    
    
    // Предназначен для получения результата валидации подписи БЕЗ проверкой цепочки сертификатов
    //
    abstract public function execNoChain(array $filePaths):string;
}