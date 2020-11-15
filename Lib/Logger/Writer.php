<?php


namespace Lib\Logger;

use Exception as SelfEx;

use core\Classes\Request\Request;
use core\Classes\Session;



class Writer extends Logger
{
    
    private string $path;


    public function __construct(string $path)
    {
        if (!is_file($path) || !is_writable($path)) {
            throw new SelfEx("Указанный путь: '{$path}' должен указывать на существующий файл, доступный для записи", 1);
        }
        $this->path = $path;
    }


    public function write(string $message): void
    {
        if (Request::isWeb()) {
            $author = Session::isAuthorized() ? 'id: ' . Session::getUserId() : 'Не авторизован';
        } else {
            $author = 'cron';
        }

        $line = [
            parent::DATE       => date('d.m.Y H:i:s'),
            parent::AUTHOR     => $author,
            parent::MESSAGE    => $message,
        ];

        if (($f = fopen($this->path, 'a')) === false) {
            throw new SelfEx("Ошибка при работе функции 'fopen'", 2);
        }
        if (fputcsv($f,$line, parent::CSV_DELIMITER, parent::CSV_ENCLOSURE, parent::CSV_ESCAPE_CHAR) === false) {
            throw new SelfEx("Ошибка при работе функции 'fputcsv'", 2);
        }
        fclose($f);
    }
}