<?php


namespace Lib\CSP;

use Lib\Exceptions\CSPValidator as SelfEx;
use Lib\Exceptions\Shell as ShellEx;
use Lib\Exceptions\CSPMessageParser as CSPMessageParserEx;
use functions\Exceptions\Functions as FunctionsEx;
use Lib\CSP\Interfaces\SignatureValidationShell;


/**
 * Предназначен для формирования массива результатов на основе вывода исполняемой команды
 *
 */
class Validator
{

    private MessageParser $parser;
    private SignatureValidationShell $shell;

    /**
     * Код последней ошибки
     *
     */
    private ?string $lastErrorCode = null;


    /**
     * Конструктор класса
     *
     * @param MessageParser $parser экземпляр класса разбора вывода cmd-сообщения
     * @param SignatureValidationShell $shell экземпляр класса для выполения shell-команд (ExternalSignature / InternalSignature)
     */
    public function __construct(MessageParser $parser, SignatureValidationShell $shell)
    {
        $this->parser = $parser;
        $this->shell = $shell;
    }


    /**
     * Предназначен для формирования массива с результатами валидации ЭЦП файла
     *
     * @param string ...$paths <i>перечисление</i> путей к файлам:<br>
     * В случае shell = InternalSignature передается 1 параметр - абсолютный путь в ФС сервера к файлу со встроенной подписью<br>
     * В случае shell = ExternalSignature передается 2 параметра - абсолютный путь в ФС сервера к файлу, абсолютный путь в ФС сервера к файлу открепленной подписи
     * @return array массив формата:<br>
     * 0 (array) :
     * <br><br>
     * fio (string) : Фамилия Имя Отчество
     * <br><br>
     * certificate (string) : данные сертификата
     * <br><br>
     * signature_verify (array) : <br>
     * result (bool)            : верна подпись или нет<br>
     * message (string)         : вывод исполняемой команды результата проверки подписи<br>
     * user_message (string)    : пользовательское сообщение на основе результата проверки подписи<br>
     * <br>
     * certificate_verify (array) : <br>
     * result (bool)              : сертификат действителен или нет<br>
     * message (string)           : вывод исполняемой команды результата проверки подписи (сертификата)<br>
     * user_message (string)      : пользовательское сообщение на основе результата проверки подписи (сертификата)<br>
     * <br>
     * 1 (array) : ...
     *
     * @throws ShellEx
     * @throws SelfEx
     * @throws CSPMessageParserEx
     * @throws FunctionsEx
     */
    public function validate(string ...$paths): array
    {
        // Получение результатов валидации подписи С проверкой цепочки сертификатов
        $errChain_message = $this->shell->execErrChain($paths);
        $errChain_messageParts = $this->parser->getMessagePartsWithoutTechnicalPart($errChain_message);

        list('errorCode' => $this->lastErrorCode, 'signers' => $errChain_signers) = $this->getValidateResults($errChain_messageParts);

        foreach ($errChain_signers as &$signer) {

            // Результат с проверкой цепочки сертификатов валидный
            // Значит и подпись и её сертификат валидный
            //
            // или
            //
            // Результат с проверкой цепочки сертификатов невалидный и при этом есть ошибки, что подпись невалидная
            // (если открепленная подпись не соответствует файлу, это сообщение (Error: Invalid Signature.) выйдет первым, даже если сертификат уже просрочен,
            //  поэтому нет смысла в повторной проверке подписи без проверки цепочки сертификатов)
            if (
                $signer['result']
                || (!$signer['result'] && ($signer['message'] == 'Error: Invalid Signature.' || $signer['message'] == 'Error: Invalid algorithm specified.'))
            ) {

                $signatureVerify = [
                    'result'       => $signer['result'],
                    'message'      => $signer['message'],
                    'user_message' => $this->getSignatureUserMessage($signer['message'])
                ];

           // Результат с проверкой цепочки сертификатов невалидный и при этом нет ошибки, что подпись невалидная или использован недействительный алгоритм подписи
           // Производим повторную проверку подписи без проверки цепочки сертификатов
            } else {

                // Получение результатов валидации подписи БЕЗ проверкой цепочки сертификатов
                if (!isset($noChain_signers)) {

                    $noChain_message = $this->shell->execNoChain($paths);
                    $noChain_messageParts = $this->parser->getMessagePartsWithoutTechnicalPart($noChain_message);

                    list('errorCode' => $this->lastErrorCode, 'signers' => $noChain_signers) = $this->getValidateResults($noChain_messageParts);
                }

                // Находим текущий итерируемый signer среди signers нового результата валидаци
                $ind = getFirstArrayEntryIndex($noChain_signers, 'certificate', $signer['certificate']);

                if (is_null($ind)) {
                    throw new SelfEx('В результате проверки БЕЗ цепочки сертификатов не был найден подписант из результатов проверки С цепочкой сертификатов', 6);
                }
                $noChain_signer = $noChain_signers[$ind];

                // Результат проверки сертификата остается от проверки с цепочкой сертификата ------------------
                $signatureVerify = [
                    'result'       => $noChain_signer['result'],
                    'message'      => $noChain_signer['message'],
                    'user_message' => $this->getSignatureUserMessage($noChain_signer['message'])
                ];
            }

            $certificateVerify = [
                'result'       => $signer['result'],
                'message'      => $signer['message'],
                'user_message' => $this->getCertificateUserMessage($signer['message'])
            ];

            // Добавляем нужные поля
            $signer['signature_verify'] = $signatureVerify;
            $signer['certificate_verify'] = $certificateVerify;
            // Удаляем не нужные поля
            unset($signer['result'], $signer['message']);
        }
        unset($signer);

        return $errChain_signers;
    }


    /**
     * Предназначен для формирования массива с результатами проверки частей сообщения
     *
     * @param array $messageParts
     * @return array массив формата:<br>
     * signers (array) :
     * <br><br>
     * 0 (array) :
     * <br><br>
     * fio (string)         : Фамилия Имя Отчество<br>
     * certificate (string) : данные сертификата<br>
     * result (bool)        : результат проверки подписи<br>
     * message (string)     : сообщение результата проверки подписи<br>
     * <br>
     * 1 (array) : ...
     * <br><br>
     * errorCode (string) : код ошибки
     *
     * @throws SelfEx
     * @throws FunctionsEx
     * @throws CSPMessageParserEx
     */
    private function getValidateResults(array $messageParts): array
    {
        $signers = [];
        $errorCodes = [];

        for ($l = 0; $l < count($messageParts); $l++) {

            $part = $messageParts[$l];

            // Во входном массиве частей сообщения могут быть элементы:
            //      Signer: ...
            //          После подписанта:
            //              Signature's verified.
            //                  или:
            //              Сообщение об ошибке И
            //              Error: Signature.
            //                  или:
            //              Сообщение об ошибке И
            //              Signer: ... (следующий подписант)
            //      Error: ...
            //      ErrorCode: ...
            if (contains($part, 'Signer:')) {

                $FIO = $this->parser->getFIO($part);

                // Signature's verified. ИЛИ Сообщение об ошибке
                $next_1_part = $messageParts[$l + 1];
                // В случае если next_1_part - сообщение об ошибке:
                //    Если один ИЛИ (несколько подписантов и текущий последний)
                //       Error: Signature.
                //    Если несколько подписантов И (текущий не последний)
                //       Signer: ... (следующий подписант)
                $next_2_part = $messageParts[$l + 2];

                if ($next_1_part == "Signature's verified.") {

                    $verifyResult = true;
                    $l += 1; // Перескакиваем через Signature's verified.
                } elseif ($next_2_part == "Error: Signature.") {

                    $verifyResult = false;
                    $l += 2; // Перескакиваем через сообщение об ошибке и Error: Signature.
                } elseif (icontainsAll($next_2_part, 'Signer:')) {

                    $verifyResult = false;
                    $l += 1; // Перескакиваем через сообщение об ошибке и переходим к следующему подписанту
                } else {

                    throw new SelfEx("Неизвестный формат частей сообщения, следующий за Signer: next_1_part='{$next_1_part}', next_2_part='{$next_2_part}'" . $this->getDebugMessageParts($messageParts), 2);
                }

                // Массив с данными о подписи
                $signers[] = [
                    'fio'         => $FIO,
                    'certificate' => $this->parser->getCertificateInfo($part),
                    'result'      => $verifyResult,
                    'message'     => $next_1_part
                ];
            } elseif (contains($part, 'ErrorCode:')) {

                $errorCodes[] = $this->parser->getErrorCode($part);
            } elseif (containsAny(
                $part,
                'Error: Invalid cryptographic message type.',
                'Error: The parameter is incorrect.',
                'Error: The streamed cryptographic message is not ready to return data.',
                "Error: Can't open file",
                'Unknown error.')
            ){

                continue; // Ошибки пропускаем, т.к. дальше (в следующих итерациях) отловится в ErrorCode
            } else {
                // В данную ветку ничего не должно попасть, т.к. блоки Signer и ErrorCode обрабатываются выше
                throw new SelfEx("Неизвестная часть сообщения: '{$part}'" . $this->getDebugMessageParts($messageParts), 3);
            }
        }

        // Проверки на единственную часть ErrorCode и существование одного и более Signers
        $count_errorCodes = count($errorCodes);
        if ($count_errorCodes != 1) {
            throw new SelfEx("Получено некорректное количество блоков ErrorCode: ({$count_errorCodes})" . $this->getDebugMessageParts($messageParts), 5);
        }

        if (empty($signers)) {
            $this->lastErrorCode = $errorCodes[0]; // Запись последнего кода ошибки
            throw new SelfEx("В частях сообщения отсустсвует(ют) Signer" . $this->getDebugMessageParts($messageParts), 4);
        }

        return [
            'signers'   => $signers,
            'errorCode' => $errorCodes[0]
        ];
    }


    /**
     * Преднажначен для полученя пользовательского сообщения на основе результата проверки подписи
     *
     * @param string $verifyMessage результат проверки подписи
     * @return string пользовательское сообщение
     * @throws SelfEx
     */
    private function getSignatureUserMessage(string $verifyMessage): string
    {
        switch ($verifyMessage) {
            case "Signature's verified." :
                return "Подпись действительна";

            case "Error: Invalid algorithm specified." :
                return "Подпись имеет недействительный алгоритм";

            case "Error: Invalid Signature." :
                return "Подпись не соответствует файлу";

            default :
                throw new SelfEx("Получен неизвестный результат проверки подписи: '{$verifyMessage}'", 1);
        }
    }


    /**
     * Преднажначен для полученя пользовательского сообщения на основе результата проверки подписи (сертификата)
     *
     * @param string $verifyMessage результат проверки подписи (сертификата)
     * @return string пользовательское сообщение
     * @throws SelfEx
     */
    private function getCertificateUserMessage(string $verifyMessage): string
    {

        switch ($verifyMessage) {
            case "Signature's verified." :
                return "Сертификат действителен";

            case "This certificate or one of the certificates in the certificate chain is not time valid." :
                return "Срок действия одного из сертификатов цепочки истек или еще не наступил";

            case "Trust for this certificate or one of the certificates in the certificate chain has been revoked." :
                return "Один из сертификатов цепочки аннулирован";

            case "Error: Invalid algorithm specified." :
            case "Error: Invalid Signature." :
                return "Сертификат не проверялся";

            default :
                throw new SelfEx("Получен неизвестный результат проверки сертификата: '{$verifyMessage}'", 1);
        }
    }


    /**
     * Предназначен для получения debug-строки о имеющихся частях сообщения
     *
     * @param array $messageParts
     * @return string
     */
    private function getDebugMessageParts(array $messageParts): string
    {
        $tmp = implode(' || ', $messageParts);
        return ". Части сообщения (messageParts): '{$tmp}'";
    }


    /**
     * Проверка на "Недействительный типу криптографичесого сообщения"
     *
     * @return bool
     */
    public function isInvalidMessageType(): bool
    {
        return $this->lastErrorCode === '0x80091004';
    }


    /**
     * Проверка на "Передан некорректный параметр"
     *
     * Для встроенной подписи ошибка означает: проверяется файл открепленной подписи
     *
     * @return bool
     */
    public function isIncorrectParameter(): bool
    {
        return $this->lastErrorCode === '0x00000057';
    }


    /**
     * Проверка на "Потоковое криптографическое сообщение не готово для возврата данных"
     *
     * Данная ошибка означает, что был передан пустой файл
     *
     * @return bool
     */
    public function isCSPNotReadyToReturnData(): bool
    {
        return $this->lastErrorCode === '0x80091010';
    }


    /**
     * Проверка на "Не удалось открыть файл"
     *
     * Для открепленной подписи ошибка означает, что был передан пустой файл
     *
     * @return bool
     */
    public function isCantOpenFile(): bool
    {
        return $this->lastErrorCode === '0x20000065';
    }


    /**
     * Проверка на "Проверка подписи не началась"
     *
     * Для открепленной подписи ошибка означает: проверяется файл без подписи и файл без подписи
     *
     * @return bool
     */
    public function isSignatureVerifyingNotStarted(): bool
    {
        return $this->lastErrorCode === '0xffffffff';
    }
}