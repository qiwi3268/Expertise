<?php


namespace Lib\CSP;
use Lib\Exceptions\Shell as ShellEx;


/**
 * Предназначен для получения вывода исполняемой команды по валидации открепленной подписи
 *
 */
class ExternalSignature implements Interfaces\SignatureValidationShell
{

    /**
     * Предназначен для получения результата валидации открепленной подписи <b>С</b> проверкой цепочки сертификатов
     *
     * @param array $filePaths массив с путями файлов, где:<br>
     * filePaths[0] - абсолютный путь в ФС сервера к файлу<br>
     * filePaths[1] - абсолютный путь в ФС сервера к файлу открепленной подписи
     * @return string вывод исполняемой команды
     * @throws ShellEx
     */
    public function execErrChain(array $filePaths): string
    {
        // -mca      : поиск сертификатов осуществляется в хранилище компьютера CA
        // -all      : использовать все найденные сертификаты
        // -errchain : завершать выполнение с ошибкой, если хотя бы один сертификат не прошел проверку
        // -verall   : проверять все подписи
        $cmd = sprintf('%s -verify -mca -all -errchain -verall -detached "%s" "%s" 2>&1', Shell::CPROCSP, $filePaths[0], $filePaths[1]);
        return Shell::exec($cmd);
    }


    /**
     * Предназначен для получения результата валидации открепленной подписи <b>БЕЗ</b> проверки цепочки сертификатов
     *
     * @param array $filePaths массив с путями файлов, где:<br>
     * filePaths[0] - абсолютный путь в ФС сервера к файлу<br>
     * filePaths[1] - абсолютный путь в ФС сервера к файлу открепленной подписи
     * @return string вывод исполняемой команды
     * @throws ShellEx
     */
    public function execNoChain(array $filePaths): string
    {
        // -nochain : не проверять цепочки найденных сертификатов
        // -verall  : проверять все подписи
        $cmd = sprintf('%s -verify -nochain -verall -detached "%s" "%s" 2>&1', Shell::CPROCSP, $filePaths[0], $filePaths[1]);
        return Shell::exec($cmd);
    }
}