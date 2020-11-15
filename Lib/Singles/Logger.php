<?php


namespace Lib\Singles;

use Lib\Exceptions\Logger as SelfEx;
use Exception;

use core\Classes\Request\Request;
use core\Classes\Session;



/**
 * Предназначен для логирования действий
 *
 */
class Logger
{

    private string $path;


    /**
     * Конструктор класса
     *
     * @param string $path абсолютный путь в ФС сервера до файла логов
     * @throws SelfEx
     */
    public function __construct(string $path)
    {
        if (!is_file($path) || !is_writable($path)) {
            throw new SelfEx("Указанный путь: '{$path}' должен указывать на существующий файл, доступный для записи", 1);
        }
        $this->path = $path;
    }


    /**
     * Предназначен для записи логов исключений
     *
     * @uses \Lib\Singles\Logger::write()
     * @param Exception $e логируемое исключение
     * @param string $description дополнительное описание
     * @return string временная отметка логируемого сообщения
     * @throws SelfEx
     */
    public function writeException(Exception $e, string $description = ''): string
    {
        return $this->write(exceptionToString($e, $description));
    }


    /**
     * Предназначен для записи логов
     *
     * @param string $message логируемое сообщение
     * @return string временная отметка логируемого сообщения
     * @throws SelfEx
     */
    public function write(string $message): string
    {
        $date = date('d.m.Y H:i:s');

        if (Request::isWeb()) {
            $author = Session::isAuthorized() ? 'id: ' . Session::getUserId() : 'Не авторизован';
        } else {
            $author = 'cron';
        }
        $message = "{$date} | {$author} | {$message}" . PHP_EOL;
        if (file_put_contents($this->path, $message, FILE_APPEND) === false) {
            throw new SelfEx('Произошла ошибка при попытке записать логируемое сообщение', 2);
        }
        return $date;
    }
}