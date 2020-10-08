<?php


namespace Lib\CSP;

use Lib\Exceptions\Shell as SelfEx;


/**
 * Предназначен для выполнения команды через оболочку и возврата вывода в виде строки
 *
 */
class Shell
{

    /**
     * Путь в ФС сервера к утилите cryptcp
     *
     */
    public const CPROCSP = '/opt/cprocsp/bin/amd64/cryptcp';


    /**
     * Предназначен для исполнения cmd команды cryptcp утилиты
     *
     * @param string $cmd cmd команда
     * @return string вывод исполняемой команды
     * @throws SelfEx
     */
    static public function exec(string $cmd): string
    {
        $message = shell_exec($cmd);

        if (is_null($message)) {
            throw new SelfEx("Исполняемая команда: '{$cmd}' не произвела вывод или произошла ошибка", 1);
        }
        return $message;
    }
}