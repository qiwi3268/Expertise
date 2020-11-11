<?php


namespace APIControllers\Home;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Shell as ShellEx;
use functions\Exceptions\Functions as FunctionsEx;

use core\Classes\Request\HttpRequest;
use core\Classes\ControllersInterface\APIController;
use Lib\Singles\Logger;
use Lib\CSP\MessageParser;
use Lib\CSP\FileHash;
use Classes\Application\Helpers\Helper as ApplicationHelper;

//Блок проверки маппинга - не нужен, т.к. производится на предыдущем этапе - в file_checker


/**
 * API предназначен для получения криптографического hash'а к требуемому файлу (для дальнейшего подписания на клиенсткой стороне)
 *
 * <b>***</b> Предполагается, что перед использованием данного API был вызов FileChecker
 *
 * API result:
 * - ok - ['hash']
 * - 1 - Получен неопределенный алгоритм подписи
 * - 2 - Произошла ошибка при парсинге входного параметра fs_name
 * - 3 - Произошла ошибка при выполнении shell-команды
 * - 4 - Произошла ошибка при получении ErrorCode
 * - 5 - Исполняемая команда по получению hash-файла завершилась с ошибкой
 * - 6 - Произошла ошибка при чтении созданного hash-файла
 * - 7 - Произошла ошибка при удалении созданного hash-файла
 *
 */
class FileHashGetter extends APIController
{


    /**
     * Реализация абстрактного метода
     *
     * @throws RequestEx
     * @throws DataBaseEx
     */
    public function doExecute(): void
    {
        list(
            'sign_algorithm' => $sign_algorithm,
            'fs_name'        => $fs_name
            ) = $this->getCheckedRequiredParams(HttpRequest::POST, ['sign_algorithm', 'fs_name']);

        // Проверка существования указанного алгоритма попдписи
        if (!isset(SIGN_ALGORITHMS[$sign_algorithm])) {
            $this->logAndErrorExit(1, "Получен неопределенный алгоритм подписи: '{$sign_algorithm}'");
        }

        try {
            $fileName = ApplicationHelper::parseApplicationFilePath($fs_name)['file_name'];
        } catch (FunctionsEx $e) {
            $this->logAndExceptionExit(2, $e, "Произошла ошибка при парсинге входного параметра fs_name");
        }

        // Получаем алгоритм хэширования на основе алгоритма подписи
        $hashAlgorithm = HASH_ALGORITHMS[$sign_algorithm];

        try {
            // Выполняем команду, на основе которой сгенерируется hash-файл
            $message = (new FileHash())->execHash(TMP_HASH_FILES, $hashAlgorithm, $fs_name);
        } catch (ShellEx $e) {
            $this->logAndExceptionExit(3, $e, "Произошла ошибка при выполнении shell-команды");
        }

        // Проверка вывода исполняемой команды
        $messageParser = new MessageParser(false);

        try {
            $errorCode = $messageParser->getErrorCode($message);
        } catch (FunctionsEx $e) {
            // Произошла ошибка или нет вхождений ErrorCode
            $this->logAndExceptionExit(4, $e, "Произошла ошибка при получении ErrorCode");
        }

        // ErrorCode не соответствует успешному выполнению команды
        if ($errorCode != MessageParser::OK_ERROR_CODE) {
            $this->logAndErrorExit(5, "Исполняемая команда по получению hash-файла завершилась с ошибкой. [ErrorCode: {$errorCode}]");
        }

        // Путь к созданному hash-файлу (с расширением .hsh)
        $hash_filePath = TMP_HASH_FILES . "/{$fileName}.hsh";

        $hash_data = file_get_contents($hash_filePath);

        if ($hash_data === false) {
            $this->logAndErrorExit(6, "Произошла ошибка при чтении созданного hash-файла: '{$hash_filePath}'");
        }

        // Удаляем временный hash-файл
        if (!unlink($hash_filePath)) {
            $this->logAndErrorExit(7, "Произошла ошибка при удалении созданного hash-файла: '{$hash_filePath}'");
        }

        // Все прошло успешно
        $this->successExit(['hash' => $hash_data]);
    }


    /**
     * Реализация абстрактного метода
     *
     */
    protected function getErrorLogger(): Logger
    {
        return new Logger(LOGS_API_ERRORS, 'FileHashGetter.log');
    }
}