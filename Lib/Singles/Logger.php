<?php


namespace Lib\Singles;

use Exception;
use core\Classes\Session;
use Lib\Exceptions\Logger as SelfEx;


/**
 * Предназначен для логирования действий
 *
 */
class Logger
{

    private string $logsDir;
    private string $logsName;


    /**
     * Конструктор класса
     *
     * @param string $logsDir абсолютный путь в ФС сервера до директории логов. Без символа '/' в конце строки
     * @param string $logsName название файла логов от директории логов, включая расширение файла
     * @throws SelfEx
     */
    public function __construct(string $logsDir, string $logsName)
    {
        $this->validateLogsDir($logsDir);
        $this->validateLogsName($logsName);
        $this->checkExistAndWritable("{$logsDir}/{$logsName}");
        $this->logsDir = $logsDir;
        $this->logsName = $logsName;
    }


    /**
     * Предназначен для смены свойства класса с учетом проверки
     *
     * @param string $logsDir абсолютный путь в ФС сервера до директории логов
     * @throws SelfEx
     */
    public function changeLogsDir(string $logsDir): void
    {
        $this->validateLogsDir($logsDir);
        $this->checkExistAndWritable("{$logsDir}/{$this->logsName}");
        $this->logsDir = $logsDir;
    }


    /**
     * Предназначен для смены свойства класса с учетом проверки
     *
     * @param string $logsName имя файла логов
     * @throws SelfEx
     */
    public function changeLogsName(string $logsName): void
    {
        $this->validateLogsName($logsName);
        $this->checkExistAndWritable("{$this->logsDir}/{$logsName}");
        $this->logsName = $logsName;
    }


    /**
     * Предназначен для записи логов исключений
     *
     * @uses \Lib\Singles\Logger::write()
     * @param Exception $e логируемое исключение
     * @param string|null $description описание (часть перед сообщением и кода исключения).<br>
     * <i>Без точки и пробелов в конце</i>
     * @return string временная отметка логируемого сообщения
     * @throws SelfEx
     */
    public function writeException(Exception $e, ?string $description = null): string
    {
        $description = !is_null($description) ? "{$description}. " : "";

        $class = get_class($e);

        $message = "{$description}Exception: {$class}. Message: {$e->getMessage()}. Code: {$e->getCode()}";
        return $this->write($message);
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

        $user = 'Пользователь';
        if (Session::isAuthorized()) {

            $id = Session::getUserId();
            $user = "{$user} id: {$id}";
        } else {
            $user = "{$user} не авторизован";
        }
        $message = "{$date} | {$user} | {$message}" . PHP_EOL;
        if (file_put_contents("{$this->logsDir}/{$this->logsName}", $message, FILE_APPEND) === false) {
            throw new SelfEx("Произошла ошибка при попытке записать логируемое сообщение: '{$message}' в файл: '{$this->logsDir}/{$this->logsName}'", 5);
        }
        return $date;
    }


    /**
     * Предназначен для валидации директории файла логов
     *
     * Путь к директории должен начинться с '/' и не должен заканчиваться на '/'
     *
     * @param string $logsDir абсолютный путь в ФС сервера до директории логов
     * @throws SelfEx
     */
    private function validateLogsDir(string $logsDir): void
    {
        if ($logsDir[0] != '/' || $logsDir[mb_strlen($logsDir) - 1] == '/') {
            throw new SelfEx("Передан некорректный параметр logsDir: '{$logsDir}'. Путь к директории должен начинться с '/' и не должен заканчиваться на '/'", 1);
        }
    }


    /**
     * Предназначен для валидации имени файла логов
     *
     * Название файла не должно начинаться с '/'
     *
     * @param string $logsName имя файла логов
     * @throws SelfEx
     */
    private function validateLogsName(string $logsName): void
    {
        if ($logsName[0] == '/') throw new SelfEx("Передан некорректный параметр logsName: '{$logsName}'. Название файла не должно начинаться с '/'", 2);
    }


    /**
     * Предназначен для проверки существования указанного файла логов и на его доступность для записи
     *
     * @param string $path бсолютный путь в ФС сервера к файлу логов
     * @throws SelfEx
     */
    private function checkExistAndWritable(string $path): void
    {
        if (!file_exists($path)) throw new SelfEx("Указанный лог файл: '{$path}' не существует в файловой системе сервера", 3);
        elseif (!is_writable($path)) throw new SelfEx("Указанный лог файл: '{$path}' не доступен для записи", 4);
    }
}