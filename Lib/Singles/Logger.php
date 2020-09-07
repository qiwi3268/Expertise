<?php


namespace Lib\Singles;

use Lib\Exceptions\Logger as SelfEx;


// Предназначен для логирования действий
//
class Logger
{

    private string $logsDir;
    private string $logsName;


    // Принимает параметры-----------------------------------
    // logsDir  string : абсолютный путь на сервере до директории логов. Без символа '/' в конце строки
    // logsName string : название файла логов от директории логов, включая расширение файла
    //
    function __construct(string $logsDir, string $logsName)
    {
        $this->validateLogsDir($logsDir);
        $this->validateLogsName($logsName);
        $this->checkExistAndWritable("{$logsDir}/{$logsName}");
        $this->logsDir = $logsDir;
        $this->logsName = $logsName;
    }


    // Предназначен для смены свойства класса с учетом проверки
    // Принимает параметры-----------------------------------
    // logsDir string: абсолютный путь к директории с файлами логов
    //
    public function changeLogsDir(string $logsDir): void
    {
        $this->validateLogsDir($logsDir);
        $this->checkExistAndWritable("{$logsDir}/{$this->logsName}");
        $this->logsDir = $logsDir;
    }


    // Предназначен для смены свойства класса с учетом проверки
    // Принимает параметры-----------------------------------
    // logsName string: имя файла логов
    //
    public function changeLogsName(string $logsName): void
    {
        $this->validateLogsName($logsName);
        $this->checkExistAndWritable("{$this->logsDir}/{$logsName}");
        $this->logsName = $logsName;
    }


    // Предназначен для записи логов
    // Принимает параметры-----------------------------------
    // message string: логируемое сообщение
    // Возвращает параметры-----------------------------------
    // string : временная отметка логируемого сообщения
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Logger :
    // code:
    //  5 - произошла ошибка при попытке записать логируемое сообщение
    //
    public function write(string $message): string
    {
        $date = date('d.m.Y H:i:s');
        $message = "{$date} {$message}" . PHP_EOL;
        if (file_put_contents("{$this->logsDir}/{$this->logsName}", $message, FILE_APPEND) === false) {
            throw new SelfEx("Произошла ошибка при попытке записать логируемое сообщение: '{$message}' в файл: '{$this->logsDir}/{$this->logsName}'", 5);
        }
        return $date;
    }


    // Предназначен для валидации директории файла логов
    // Путь к директории должен начинться с '/' и не должен заканчиваться на '/'
    // Принимает параметры-----------------------------------
    // logsDir string: абсолютный путь к директории с файлами логов
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Logger :
    // code:
    //  1 - передан некорректный параметр logsDir
    //
    private function validateLogsDir(string $logsDir): void
    {
        if ($logsDir[0] != '/' || $logsDir[mb_strlen($logsDir) - 1] == '/') {
            throw new SelfEx("Передан некорректный параметр logsDir: '{$logsDir}'. Путь к директории должен начинться с '/' и не должен заканчиваться на '/'", 1);
        }
    }


    // Предназначен для валидации имени файла логов
    // Название файла не должно начинаться с '/'
    // Принимает параметры-----------------------------------
    // logsName string: имя файла логов
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Logger :
    // code:
    //  2 - передан некорректный параметр logsName
    //
    private function validateLogsName(string $logsName): void
    {
        if ($logsName[0] == '/') throw new SelfEx("Передан некорректный параметр logsName: '{$logsName}'. Название файла не должно начинаться с '/'", 2);
    }


    // Предназначен для проверки существования указанного файла логов и на его доступность для записи
    // Принимает параметры-----------------------------------
    // path string: абсолютный путь в ФС сервера к файлу логов
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Logger :
    // code:
    //  3 - указанный лог файл не существует в файловой системе сервера
    //  4 - указанный лог файл не доступен для записи
    //
    private function checkExistAndWritable(string $path): void
    {
        if (!file_exists($path)) throw new SelfEx("Указанный лог файл: '{$path}' не существует в файловой системе сервера", 3);
        elseif (!is_writable($path)) throw new SelfEx("Указанный лог файл: '{$path}' не доступен для записи", 4);
    }
}