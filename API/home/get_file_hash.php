<?php

use functions\Exceptions\Functions as FunctionsEx;
use Lib\Exceptions\Shell as ShellEx;
use Lib\Exceptions\Logger as LoggerEx;

use Lib\Singles\Logger;
use Classes\Application\Helpers\Helper as ApplicationHelper;

use Lib\CSP\MessageParser;
use Lib\CSP\FileHash;


// API предназначен для получения криптографического hash'а к требуемому файлу (для дальнейшего подписания на клиенсткой стороне)
// ***
//
// API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//  2  - Получен неопределенный алгоритм подписи
//       {result, error_message : текст ошибки}
//  3  - Произошла ошибка при парсинге fs_name
//       {result, error_message : текст ошибки}
//  4  - Произошла ошибка при выполнении cmd-команды
//       {result, message : текст ошибки, code: код ошибки}
//  5  - Произошла ошибка при парсинге вывода исполняемой команды (нет ErrorCode)
//       {result, message : текст ошибки, code: код ошибки}
//	6  - Исполняемая команда по получению hash-файла завершилась с ошибкой
//       {result, error_message : текст ошибки}
//	7  - Произошла ошибка при чтении созданного hash-файла
//       {result, error_message : текст ошибки}
//	8  - Произошла ошибка при удалении созданного hash-файла
//       {result, error_message : текст ошибки}
//  9  - Все прошло успешно
//       {result, error_message : текст ошибки}
//  10 - Ошибка при работе с Logger
//       {result, message : текст ошибки, code: код ошибки}
//  11 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}

// Проверка наличия обязательных параметров
if (!checkParamsPOST('sign_algorithm', 'fs_name')) {

    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try {

    /** @var string $P_sign_algorithm */
    /** @var string $P_fs_name */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    $logger = new Logger(LOGS . '/csp/errors/API_get_file_hash.log');

    // Проверка заявителя на доступ к заявлению не нужна, т.к. производится на предыдущем этапе - в file_checker
    // Блок проверки маппинга - не нужен, т.к. производится на предыдущем этапе - в file_checker

    // Проверка существования указанного алгоритма попдписи
    if (!isset(SIGN_ALGORITHMS[$P_sign_algorithm])) {
        $errorMessage = "Получен неопределенный алгоритм подписи: '{$P_sign_algorithm}'";
        $logger->write($errorMessage);

        exit(json_encode([
            'result'        => 2,
            'error_message' => $errorMessage
        ]));
    }

    try {

        list('application_id' => $applicationId, 'file_name' => $fileName) = ApplicationHelper::parseApplicationFilePath($P_fs_name);
    } catch (FunctionsEx $e) {

        // Произошла ошибка при парсинге P_fs_name
        $errorMessage = $e->getMessage();
        $logger->write($errorMessage);

        exit(json_encode([
            'result'        => 3,
            'error_message' => $errorMessage,
        ]));
    }

    // Получаем алгоритм хэширования на основе алгоритма подписи
    $hashAlgorithm = HASH_ALGORITHMS[$P_sign_algorithm];

    $fileHash = new FileHash();
    try {

        // Выполняем команду, на основе которой сгенерируется hash-файл
        $message = $fileHash->execHash(TMP_HASH_FILES, $hashAlgorithm, $P_fs_name);
        unset($fileHash);
    } catch (ShellEx $e) {

        $errorMessage = $e->getMessage();
        $logger->write($errorMessage);

        exit(json_encode([
            'result'  => 4,
            'message' => $errorMessage,
            'code'    => $e->getCode()
        ]));
    }

    // Проверка вывода исполняемой команды
    $messageParser = new MessageParser(false);

    try {

        $errorCode = $messageParser->getErrorCode($message);
    } catch (FunctionsEx $e) {

        // Произошла ошибка или нет вхождений ErrorCode
        $errorMessage = $e->getMessage();
        $logger->write($errorMessage);

        exit(json_encode([
            'result'  => 5,
            'message' => $errorMessage,
            'code'    => $e->getCode()
        ]));
    }

    // ErrorCode не соответствует успешному выполнению команды
    if ($errorCode != $messageParser::OK_ERROR_CODE) {
        $errorMessage = "Исполняемая команда по получению hash-файла завершилась с ошибкой. [ErrorCode: {$errorCode}]";
        $logger->write($errorMessage);

        exit(json_encode([
            'result'        => 6,
            'error_message' => $errorMessage
        ]));
    }

    // Путь к созданному hash-файлу (с расширением .hsh)
    $hash_filePath = TMP_HASH_FILES . "/{$fileName}.hsh";

    $hash_data = file_get_contents($hash_filePath);

    if ($hash_data === false) {
        $errorMessage = "Произошла ошибка при чтении созданного hash-файла: '{$hash_filePath}'";
        $logger->write($errorMessage);

        exit(json_encode([
            'result'        => 7,
            'error_message' => $errorMessage
        ]));
    }

    // Удаляем временный hash-файл
    if (!unlink($hash_filePath)) {
        $errorMessage = "Произошла ошибка при удалении созданного hash-файла: '{$hash_filePath}'";
        $logger->write($errorMessage);

        exit(json_encode([
            'result'        => 8,
            'error_message' => $errorMessage
        ]));
    }

    // Все прошло успешно
    exit(json_encode([
        'result' => 9,
        'hash'   => $hash_data
    ]));

} catch (LoggerEx $e) {

    exit(json_encode([
        'result'  => 10,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
} catch (Exception $e) {

    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
    $logger->write("Произошла непредвиденная ошибка. Message: '{$errorMessage}', Code: '{$errorCode}'");

    exit(json_encode([
        'result'  => 11,
        'message' => $errorMessage,
        'code'    => $errorCode
    ]));
}