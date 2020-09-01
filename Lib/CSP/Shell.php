<?php


namespace Lib\CSP;

use Lib\Exceptions\Shell as SelfEx;


class Shell
{

    // Путь в ФС сервера к утилите cryptcp
    public const CPROCSP = '/opt/cprocsp/bin/amd64/cryptcp';


    // Предназначен для исполнения cmd команды cryptcp утилиты
    // Принимает параметры-----------------------------------
    // cmd string: cmd команда
    // Возвращает параметры----------------------------------
    // string  : вывод исполняемой команды
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Shell : исполняемая команда не произвела вывод или произошла ошибка
    //
    public static function exec(string $cmd): string
    {
        $message = shell_exec($cmd);

        if (is_null($message)) {
            throw new SelfEx("Исполняемая команда: '{$cmd}' не произвела вывод или произошла ошибка");
        }
        return $message;
    }
}