<?php


namespace Lib\CSP;


/**
 * Предназначен для получения вывода исполняемой команды по валидации встроенной подписи
 *
 */
class InternalSignature implements Interfaces\SignatureValidationShell
{

    /**
     * Предназначен для получения результата валидации встроенной подписи <b>С</b> проверкой цепочки сертификатов
     *
     * @param array $filePaths массив с путями файлов, где:<br> filePaths[0] - абсолютный путь в ФС сервера к файлу со встроенной подписью
     * @return string вывод исполняемой команды
     * @throws \Lib\Exceptions\Shell
     */
    public function execErrChain(array $filePaths): string
    {
        // -mca      : поиск сертификатов осуществляется в хранилище компьютера CA
        // -all      : использовать все найденные сертификаты
        // -errchain : завершать выполнение с ошибкой, если хотя бы один сертификат не прошел проверку
        // -verall   : проверять все подписи
        $cmd = sprintf('%s -verify -mca -all -errchain -verall "%s" 2>&1', Shell::CPROCSP, $filePaths[0]);
        return Shell::exec($cmd);
    }


    /**
     * Предназначен для получения результата валидации встроенной подписи <b><БЕЗ/b> проверки цепочки сертификатов
     *
     * @param array $filePaths массив с путями файлов, где:<br> filePaths[0] - абсолютный путь в ФС сервера к файлу со встроенной подписью
     * @return string вывод исполняемой команды
     * @throws \Lib\Exceptions\Shell
     */
    public function execNoChain(array $filePaths): string
    {
        // -nochain : не проверять цепочки найденных сертификатов
        // -verall  : проверять все подписи
        $cmd = sprintf('%s -verify -nochain -verall "%s" 2>&1', Shell::CPROCSP, $filePaths[0]);
        return Shell::exec($cmd);
    }
}