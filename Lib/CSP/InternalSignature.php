<?php


namespace Lib\CSP;


class InternalSignature implements Interfaces\SignatureValidationShell
{


    // Предназначен для получения результата валидации встроенной подписи С проверкой цепочки сертификатов
    // Принимает параметры-----------------------------------
    // filePaths array: массив с путями файлов, где filePaths[0] - абсолютный путь в ФС сервера к файлу со встроенной подписью
    // Возвращает параметры----------------------------------
    // string  : вывод исполняемой команды
    //
    public function execErrChain(array $filePaths): string
    {
        // -mca      : поиск сертификатов осуществляется в хранилище компьютера CA
        // -all      : использовать все найденные сертификаты
        // -errchain : завершать выполнение с ошибкой, если хотя бы один сертификат не прошел проверку
        // -verall   : проверять все подписи
        $cmd = sprintf('%s -verify -mca -all -errchain -verall "%s" 2>&1', Shell::CPROCSP, $filePaths[0]);
        return Shell::exec($cmd);
    }

    // Предназначен для получения результата валидации встроенной подписи БЕЗ проверки цепочки сертификатов
    //
    public function execNoChain(array $filePaths): string
    {
        // -nochain : не проверять цепочки найденных сертификатов
        // -verall  : проверять все подписи
        $cmd = sprintf('%s -verify -nochain -verall "%s" 2>&1', Shell::CPROCSP, $filePaths[0]);
        return Shell::exec($cmd);
    }
}