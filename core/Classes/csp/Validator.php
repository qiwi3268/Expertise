<?php


namespace csp;


class Validator{
    
    private MessageParser $Parser;
    private SignatureValidationShell $Shell;
    
    // Код последней ошибки
    private ?string $lastErrorCode = null;
    
    // Принимает параметры-----------------------------------
    // Parser MessageParser           : экземпляр класса парсинга вывода cmd-сообщения
    // Shell SignatureValidationShell : экземпляр класса для выполения shell-команд (ExternalSignature / InternalSignature)
    //
    public function __construct(MessageParser $Parser, SignatureValidationShell $Shell){
        $this->Parser = $Parser;
        $this->Shell = $Shell;
    }
    
    
    // Предназначен для формирования массива с результатами валидации ЭЦП файла
    // Принимает параметры-----------------------------------
    // paths string: перечисление путей к файлам
    //  В случае Shell = InternalSignature передается 1 параметр - абсолютный путь в ФС сервера к файлу со встроенной подписью
    //  В случае Shell = ExternalSignature передается 2 параметра - абсолютный путь в ФС сервера к файлу, абсолютный путь в ФС сервера к файлу открепленной подписи
    // Возвращает параметры----------------------------------
    // array : массив формата:
    //      0 : array
    //          fio         string : Фамилия Имя Отчество
    //          certificate string : данные сертификата
    //          signature_verify : array
    //              result         bool : true - подпись верна / false - в противном случае
    //              message      string : вывод исполняемой команды результата проверки подписи
    //              user_message string : пользовательское сообщение на основе результата проверки подписи
    //          certificate_verify : array
    //              result         bool : true - сертификат действителен / false - в противном случае
    //              message      string : вывод исполняемой команды результата проверки подписи (сертификата)
    //              user_message string : пользовательское сообщение на основе результата проверки подписи (сертификата)
    //          1 : array...
    // Выбрасывает исключения--------------------------------
    // CSPMessageParserException :
    // code:
    //  6 - в результате проверки БЕЗ цепочки сертификатов не был найден подписант из результатов проверки С цепочкой сертификатов
    //
    public function validate(string ...$paths):array {
        
        // Получение результатов валидации подписи С проверкой цепочки сертификатов
        $errChain_message = $this->Shell->execErrChain($paths);
        $errChain_messageParts = $this->Parser->getMessagePartsWithoutTechnicalPart($errChain_message);
        $errChain_results = $this->getValidateResults($errChain_messageParts);
        
        $this->lastErrorCode = $errChain_results['errorCode']; // Запись последнего кода ошибки
        $errChain_signers = $errChain_results['signers'];
    
        foreach($errChain_signers as &$signer){
            
            // Результат с проверкой цепочки сертификатов валидный
            // Значит и подпись и её сертификат валидный
            //
            // или
            //
            // Результат с проверкой цепочки сертификатов невалидный и при этом есть ошибки, что подпись невалидная
            // (если открепленная подпись не соответствует файлу, это сообщение (Error: Invalid Signature.) выйдет первым, даже если сертификат уже просрочен,
            //  поэтому нет смысла в повторной проверке подписи без проверки цепочки сертификатов)
            if($signer['result'] ||
              (!$signer['result'] && ($signer['message'] == 'Error: Invalid Signature.' || $signer['message'] == 'Error: Invalid algorithm specified.'))){
                
                $signatureVerify = ['result'       => $signer['result'],
                                    'message'      => $signer['message'],
                                    'user_message' => $this->getSignatureUserMessage($signer['message'])
                ];
                
            // Результат с проверкой цепочки сертификатов невалидный и при этом нет ошибки, что подпись невалидная
            // Производим повторную проверку подписи без проверки цепочки сертификатов
            }else{
    
                // Получение результатов валидации подписи БЕЗ проверкой цепочки сертификатов
                $noChain_message = $this->Shell->execNoChain($paths);
                $noChain_messageParts = $this->Parser->getMessagePartsWithoutTechnicalPart($noChain_message);
                $noChain_results = $this->getValidateResults($noChain_messageParts);
    
                $this->lastErrorCode = $noChain_results['errorCode']; // Запись последнего кода ошибки
                $noChain_signers = $noChain_results['signers'];
                
                // Находим текущий итерируемый signer среди signers нового результата валидаци
                $noChain_signer = array_filter($noChain_signers, fn($tmp_signer) => ($signer['certificate'] == $tmp_signer['certificate']));
                
                if(empty($noChain_signer)){
                    throw new \CSPValidatorException('В результате проверки БЕЗ цепочки сертификатов не был найден подписант из результатов проверки С цепочкой сертификатов', 6);
                }
                $noChain_signer = array_shift($noChain_signer);
                
                // Результат проверки сертификата остается от проверки с цепочкой сертификата ------------------
                $signatureVerify = ['result'       => $noChain_signer['result'],
                                    'message'      => $noChain_signer['message'],
                                    'user_message' => $this->getSignatureUserMessage($noChain_signer['message'])
                ];
            }
            
            $certificateVerify = ['result'       => $signer['result'],
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
    

    // Предназначен для формирования массива с результатами проверки частей сообщения
    // Возвращает параметры----------------------------------
    // array : массив формата:
    //      signers
    //          0 : array
    //              fio         string : Фамилия Имя Отчество
    //              certificate string : данные сертификата
    //              result        bool : результат проверки подписи
    //              message     string : сообщение результата проверки подписи
    //          1 : array...
    //      errorCode:
    //          код ошибки : string
    // Выбрасывает исключения--------------------------------
    // CSPMessageParserException :
    // code:
    //  1 - получен неизвестный результат проверки подписи / сертификата (подписи)
    //  2 - неизвестный формат блока, следующий за Signer
    //  3 - неизвестная часть сообщения
    //  4 - в частях сообщения отсустсвует(ют) Signer
    //  5 - получено некорректное количество блоков ErrorCode
    //
    private function getValidateResults(array $messageParts):array {
    
        $signers = [];
        $errorCodes = [];
        
        for($s = 0; $s < count($messageParts); $s++){
        
            $part = $messageParts[$s];
        
            // Во входном массиве частей сообщения могут быть элементы:
            //      Signer: ...
            //          После подписанта:
            //              Signature's verified.
            //                  или:
            //              Сообщение об ошибке И
            //              Error: Signature.
            //      ErrorCode: ...
            //      Error: The parameter is incorrect.
            //      Unknown error.
            if(icontains($part, 'Signer:')){
            
                $FIO = $this->Parser->getFIO($part);
                
                // Получаем следующие элементы за Signer
                $next_1_part = $messageParts[$s + 1]; // Signature's verified. ИЛИ Сообщение об ошибке
                $next_2_part = $messageParts[$s + 2]; // Error: Signature. В случае если next_1_part - сообщение об ошибке
            
                if($next_1_part == "Signature's verified."){
                
                    $verifyResult = true;
                    $s += 1; // Перескакиваем через Signature's verified.
                }elseif($next_2_part == "Error: Signature."){
                
                    $verifyResult = false;
                    $s += 2; // Перескакиваем через сообщение об ошибке и Error: Signature.
                }else{
                    throw new \CSPValidatorException("Неизвестный формат частей сообщения, следующий за Signer: next_1_part='{$next_1_part}', next_2_part='{$next_2_part}'".$this->getDebugMessageParts($messageParts), 2);
                }
                
                // Временный массив с данными о подписи
                $signers[] = ['fio'         => $FIO,
                              'certificate' => $this->Parser->getCertificateInfo($part),
                              'result'      => $verifyResult,
                              'message'     => $next_1_part
                ];
            
            }elseif(icontains($part, 'ErrorCode:')){
            
                $errorCodes[] = $this->Parser->getErrorCode($part);
            
            }elseif(icontains($part, 'Error: Invalid cryptographic message type.') ||
                    icontains($part, 'Error: The parameter is incorrect.') ||
                    icontains($part, 'Unknown error.')){
            
                continue; // Ошибки пропускаем, т.к. дальше (в следующих итерациях) отловится ее ErrorCode
            }else{
                // В данную ветку ничего не должно попасть, т.к. блоки Signer и ErrorCode обрабатываются выше
                throw new \CSPValidatorException("Неизвестная часть сообщения: '{$part}'".$this->getDebugMessageParts($messageParts), 3);
            }
        }
        
        // Проверки на единственную часть ErrorCode и существование одного и более Signers
        $count_errorCodes = count($errorCodes);
        if($count_errorCodes != 1){
            throw new \CSPValidatorException("Получено некорректное количество блоков ErrorCode: ({$count_errorCodes})".$this->getDebugMessageParts($messageParts), 5);
        }
        
        if(empty($signers)){
            $this->lastErrorCode = $errorCodes[0]; // Запись последнего кода ошибки
            throw new \CSPValidatorException("В частях сообщения отсустсвует(ют) Signer".$this->getDebugMessageParts($messageParts), 4);
        }
        
        return ['signers'   => $signers,
                'errorCode' => $errorCodes[0]
        ];
    }
    
    
    // Преднажначен для полученя пользовательского сообщения на основе результата проверки подписи
    // Принимает параметры-----------------------------------
    // verifyMessage string : результат проверки подписи
    // Возвращает параметры----------------------------------
    // string : пользовательское сообщение
    // Выбрасывает исключения--------------------------------
    // CSPValidatorException : получен неизвестный результат проверки подписи
    //
    private function getSignatureUserMessage(string $verifyMessage):string {
        
        switch($verifyMessage){
            case "Signature's verified.":
                return "Подпись действительна";
                
            case "Error: Invalid algorithm specified.":
                return "Подпись имеет недействительный алгоритм";
                
            case "Error: Invalid Signature.":
                return "Подпись не соответствует файлу";
                
            default:
                throw new \CSPValidatorException("Получен неизвестный результат проверки подписи: '{$verifyMessage}'", 1);
        }
    }
    
    
    // Преднажначен для полученя пользовательского сообщения на основе результата проверки подписи (сертификата)
    // Принимает параметры-----------------------------------
    // verifyMessage string : результат проверки подписи (сертификата)
    // Возвращает параметры----------------------------------
    // string : пользовательское сообщение
    // Выбрасывает исключения--------------------------------
    // CSPValidatorException : получен неизвестный результат проверки подписи (сертификата)
    //
    private function getCertificateUserMessage(string $verifyMessage):string {
        
        switch($verifyMessage){
            case "Signature's verified.":
                return "Сертификат действителен";
                
            case "This certificate or one of the certificates in the certificate chain is not time valid.":
                return "Срок действия одного из сертификатов цепочки истек или еще не наступил";
    
            case "Trust for this certificate or one of the certificates in the certificate chain has been revoked.":
                return "Один из сертификатов цепочки аннулирован";
                
            case "Error: Invalid algorithm specified.":
            case "Error: Invalid Signature.":
                return "Сертификат не проверялся";
                
            default:
                throw new \CSPValidatorException("Получен неизвестный результат проверки сертификата: '{$verifyMessage}'", 1);
        }
    }
    
    
    // Предназначен для получения debug-строки о имеющихся частях сообщения
    //
    private function getDebugMessageParts(array $messageParts):string {
        $tmp = implode(' || ', $messageParts);
        return ". Части сообщения (messageParts): '{$tmp}'";
    }
    
    
    // Предназначен для проверки последнего errorCode на тип ошибки, соответствующий
    // недействительному типу криптографичесого сообщения
    // Возвращает параметры----------------------------------
    // true  : последняя ошибка соответствует недействительному типу криптографичесого сообщения
    // false : в противном случае
    //
    public function isInvalidMessageType():bool {
        $errorCode = $this->lastErrorCode;
        if(!is_null($errorCode) && $errorCode === '0x80091004'){
            return true;
        }
        return false;
    }
    
    
    // Передан некорректный параметр
    // Для встроенной подписи ошибка означает:
    //    - проверяется файл открепленной подписи
    public function isIncorrectParameter():bool {
        $errorCode = $this->lastErrorCode;
        if(!is_null($errorCode) && $errorCode === '0x00000057'){
            return true;
        }
        return false;
    }
    
    // Проверка подписи не началась
    // Для открепленной подписи ошибка означает:
    //    - проверяется файл без подписи и файл без подписи
    public function isSignatureVerifyingNotStarted():bool {
        $errorCode = $this->lastErrorCode;
        if(!is_null($errorCode) && $errorCode === '0xffffffff'){
            return true;
        }
        return false;
    }
}