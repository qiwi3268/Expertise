<?php


use Lib\Exceptions\DataBase as DataBaseEx;
use functions\Exceptions\Functions as FunctionsEx;
use Lib\Exceptions\Shell as ShellEx;
use Lib\Exceptions\CSPMessageParser as CSPMessageParserEx;
use Lib\Exceptions\CSPValidator as CSPValidatorEx;
use Lib\Exceptions\Logger as LoggerEx;

use Lib\Singles\Logger;
use Lib\Signs\Mappings\SignsTableMapping;
use Lib\DataBase\Transaction;
use Classes\Application\Helpers\Helper as ApplicationHelper;

use Lib\CSP\MessageParser;
use Lib\CSP\InternalSignature;
use Lib\CSP\Validator;


// API предназначен для валидации открепленной подписи к файлу
// *** Предполагается, что перед использованием данного API был вызов API_file_checker для исходного файла,
//     поскольку в данном API опускаются проверки: на доступ к файлу,  на его физическое существование, на корректность маппингов и т.д.
//
// API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//	2  - Ошибка в указанном маппинге таблицы подписей
//       {result, error_message : текст ошибки}
//  3  - Произошла ошибка при парсинге fs_name_sign
//       {result, error_message : текст ошибки}
//	4  - Произошла внутренняя ошибка 'Lib\Exceptions\Shell'
//       Произошла внутренняя ошибка 'functions\Exceptions\Functions'
//       Произошла внутренняя ошибка 'Lib\Exceptions\CSPMessageParser'
//       Произошла внутренняя ошибка 'Lib\Exceptions\CSPValidator'
//       {result, error_message : текст ошибки}
//  5.1- Произошла внутренняяя ошибка (по вине входных данных):
//       Получен недействительный тип криптографичесого сообщения (проверяется файл без встроенной подписи)
//       {result, error_message : текст ошибки}
//  5.2- Произошла внутренняяя ошибка (по вине входных данных):
//       Получен некорректный параметр (проверяется файл открепленной подписи)
//       {result, error_message : текст ошибки}
//  5.3- Произошла внутренняяя ошибка (по вине входных данных):
//       Передан пустой файл
//       {result, error_message : текст ошибки}
//  6  - Произошла непредвиденная ошибка при работе метода 'Lib\CSP\Validator::validate'
//       {result, message : текст ошибки, code: код ошибки}
//  7  - Произошла ошибка при добавлении записи в таблицу подписей
//       {result, message : текст ошибки, code: код ошибки}
//  8  - Все прошло успешно
//       {result, validate_results : массив результатов валидации}
//  9  - Ошибка при работе с Logger
//       {result, message : текст ошибки, code: код ошибки}
//  10 - Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}


// Проверка наличия обязательных параметров
if (!checkParamsPOST('fs_name_sign', 'mapping_level_1', 'mapping_level_2')) {

    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}

try {

    /** @var string $P_fs_name_sign */
    /** @var string $P_mapping_level_1 */
    /** @var string $P_mapping_level_2 */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    $logger = new Logger(LOGS . '/csp/errors/API_internal_signature_verifier.log');

    // Блок проверки маппинга
    $mapping = new SignsTableMapping($P_mapping_level_1, $P_mapping_level_2);

    if (!is_null($mapping->getErrorCode())) {

        $errorMessage = $mapping->getErrorText();
        $logger->write($errorMessage);

        exit(json_encode([
            'result'        => 2,
            'error_message' => $errorMessage
        ]));
    }

    try {

        // Получение id файла
        list('application_id' => $application_id, 'file_name' => $hash_sign) = ApplicationHelper::parseApplicationFilePath($P_fs_name_sign);
    } catch (FunctionsEx $e) {

        // Произошла ошибка при парсинге P_fs_name_sign
        $errorMessage = $e->getMessage();
        $logger->write($errorMessage);

        exit(json_encode([
            'result'        => 3,
            'error_message' => $errorMessage
        ]));
    }

    $fileClassName = $mapping->getFileClassName();

    // *** Опускаем проверку на null по причине предшествующего API_file_checker
    $fileAssoc = $fileClassName::getAssocByIdMainDocumentAndHash($application_id, $hash_sign);

    $parser = new MessageParser(true);
    $shell = new InternalSignature();
    $validator = new Validator($parser, $shell);

    try {

        $validateResults = $validator->validate($P_fs_name_sign);
    } catch (ShellEx $e) {

        // Lib\CSP\Shell:exec
        // Исполняемая команда: не произвела вывод или произошла ошибка
        $date = $logger->write($e->getMessage());

        exit(json_encode([
            'result'        => 4,
            'error_message' => "Произошла внутренняя ошибка 'Lib\Exceptions\Shell'. log time: '{$date}'"
        ]));
    } catch (FunctionsEx $e) {

        // getHandlePregMatch
        // Произошла ошибка или нет вхождений шаблона при работе функции getHandlePregMatch
        $date = $logger->write($e->getMessage());

        exit(json_encode([
            'result'        => 4,
            'error_message' => "Произошла внутренняя ошибка 'functions\Exceptions\Functions'. log time: '{$date}'"
        ]));
    } catch (CSPMessageParserEx $e) {

        // Lib\CSP\MessageParser::getFIO
        $date = $logger->write($e->getMessage());
        $code = $e->getCode();

        exit(json_encode([
            'result'        => 4,
            'error_message' => "Произошла внутренняя ошибка 'Lib\Exceptions\CSPMessageParser'. code: '{$code}'. log time: '{$date}'"
        ]));
    } catch (CSPValidatorEx $e) {

        // Lib\CSP\Validator::validate
        $date = $logger->write($e->getMessage());
        $code = $e->getCode();

        // В частях сообщения отсутствует(ют) Signer

        // Последняя ошибка связана с недействительным типом сообщения
        // Для встроенной подписи ошибка означает:
        //    - проверяется файл без встроенной подписи
        if ($code == 4 && $validator->isInvalidMessageType()) {

            exit(json_encode([
                'result'        => 5.1,
                'error_message' => "Получен недействительный тип криптографичесого сообщения. code: '{$code}'. log time: '{$date}'"
            ]));
        }

        // Для встроенной подписи ошибка означает:
        //    - проверяется файл открепленной подписи
        if ($code == 4 && $validator->isIncorrectParameter() && ($fileAssoc['file_size'] / 1024 < 20)) {

            exit(json_encode([
                'result'        => 5.2,
                'error_message' => "Получен некорректный параметр. code: '{$code}'. log time: '{$date}'"
            ]));
        }

        //  Был передан пустой файл
        if ($code == 4 && $validator->isCSPNotReadyToReturnData() && ($fileAssoc['file_size'] == 0)) {

            exit(json_encode([
                'result'        => 5.3,
                'error_message' => "Передан пустой файл. code: '{$code}'. log time: '{$date}'"
            ]));
        }

        exit(json_encode([
            'result'        => 4,
            'error_message' => "Произошла внутренняяя ошибка 'Lib\Exceptions\CSPValidator'. code: '{$code}'. log time: '{$date}'"
        ]));
    } catch (Exception $e) {

        $errorMessage = $e->getMessage();
        $errorCode = $e->getCode();
        $logger->write("Произошла непредвиденная ошибка при работе метода 'Lib\CSP\Validator::validate'. Message: '{$errorMessage}', Code: '{$errorCode}'");

        exit(json_encode([
            'result'  => 6,
            'message' => $errorMessage,
            'code'    => $errorCode
        ]));
    }

    $className = $mapping->getClassName();
    $id_sign = $fileAssoc['id'];

    $transaction = new Transaction();

    // Заполняем транзакцию для создания записи в таблице подписей
    foreach ($validateResults as &$result) {

        $transaction->add($className, 'create', [
            $id_sign,
            0,
            null,
            $result['fio'],
            $result['certificate'],
            $result['signature_verify']['result'] ? 1 : 0,
            $result['signature_verify']['message'],
            $result['signature_verify']['user_message'],
            $result['certificate_verify']['result'] ? 1 : 0,
            $result['certificate_verify']['message'],
            $result['certificate_verify']['user_message']
        ]);

        // Удаляем результаты, которые не нужны на клиентской стороне
        unset($result['signature_verify']['message']);
        unset($result['certificate_verify']['message']);
    }
    unset($result);

    try {

        $transaction->start();
    } catch (DataBaseEx $e) {

        $errorMessage = $e->getMessage();
        $errorCode = $e->getCode();
        $logger->write("Произошла ошибка при добавлении записи в таблицу подписей: '{$className}'. Message: '{$errorMessage}', Code: '{$errorCode}'");

        exit(json_encode([
            'result'  => 7,
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ]));
    }

    // Все прошло успешно
    exit(json_encode([
        'result'           => 8,
        'validate_results' => $validateResults
    ]));

} catch (LoggerEx $e) {

    exit(json_encode([
        'result'  => 9,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
} catch (Exception $e) {

    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
    $logger->write("Произошла непредвиденная ошибка. Message: '{$errorMessage}', Code: '{$errorCode}'");

    exit(json_encode([
        'result'  => 10,
        'message' => $errorMessage,
        'code'    => $errorCode
    ]));
}