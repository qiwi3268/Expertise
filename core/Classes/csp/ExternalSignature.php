<?php


namespace csp;


class ExternalSignature extends \csp\Shell{
    
    
    // Предназначен для получения результата валидации открепленной подписи С проверкой цепочки сертификатов
    // Принимает параметры-----------------------------------
    // filePaths array: массив с путями файлов, где:
    //   filePaths[0] - абсолютный путь в ФС сервера к файлу
    //   filePaths[1] - абсолютный путь в ФС сервера к файлу открепленной подписи
    // Возвращает параметры----------------------------------
    // string  : вывод исполняемой команды
    //
    public function execErrChain(array $filePaths):string {
        // -mca      : поиск сертификатов осуществляется в хранилище компьютера CA
        // -all      : использовать все найденные сертификаты
        // -errchain : завершать выполнение с ошибкой, если хотя бы один сертификат не прошел проверку
        // -verall   : проверять все подписи
        $cmd = sprintf('%s -verify -mca -all -errchain -verall -detached "%s" "%s" 2>&1', parent::CPROCSP, $filePaths[0], $filePaths[1]);
        return parent::exec($cmd);
    }
    
    // Предназначен для получения результата валидации открепленной подписи БЕЗ проверки цепочки сертификатов
    //
    public function execNoChain(array $filePaths):string {
        // -nochain : не проверять цепочки найденных сертификатов
        // -verall  : проверять все подписи
        $cmd = sprintf('%s -verify -nochain -verall -detached "%s" "%s" 2>&1', parent::CPROCSP, $filePaths[0], $filePaths[1]);
        return parent::exec($cmd);
    }
}