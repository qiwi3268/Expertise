<?php


class CSP{
    

    private const CPROCSP= '/opt/cprocsp/bin/amd64/cryptcp';
    
    private array $hashNames;
    
    public function __construct(){
        
        $names = PeopleNameTable::getNames();
        foreach($names as $name) $this->hashNames[$name] = true;
    }
    
    
    // Предназначен для валидации встроенной подписи
    // path string : абсолютный путь к файлу в фс сервера
    //
    public function validateInternal(string $path){
        
        $cmd = sprintf('%s -verify -mca -all -errchain -verall "%s" 2>&1', self::CPROCSP, $path);
        
        $message = shell_exec($cmd);
        
        $messageParts = $this->getMessageWithoutTechnicalPart($message);
        
        $signers = [];
        $errorCodes = [];
        
        for($s = 0; $s < count($messageParts); $s++){
            
            $part = $messageParts[$s];
    
            if(mb_strpos($part, 'Signer:') !== false){
        
                try{
            
                    $FIO = $this->getFIO($part);
                }catch(Exception $e){
            
                }
        
                // После подписанта либо:
                //      Signature's verified.
                //      или:
                //      Сообщение об ошибке И
                //      Error: Signature.
                $next_1_part = $messageParts[$s + 1];
                $next_2_part = $messageParts[$s + 2];
                
                //todo конвертировать сообщение
                
                
                if($next_1_part == "Signature's verified."){
    
                    $verifyResult = true;
                    $verifyMessage = "Signature's verified.";
                    $s += 1; // Перескакиваем через Signature's verified.
                }elseif($next_2_part == "Error: Signature."){
    
                    $verifyResult = false;
                    $verifyMessage = $next_1_part;
                    $s += 2; // Перескакиваем через сообщение об ошибке и Error: Signature.
                }else{
                    throw new Exception('Неизвестный формат сообщения');
                }
    
                $signers[] = ['fio'         => $FIO,
                              'certificate' => $this->getCertificateInfo($part),
                              'result'      => $verifyResult,
                              'message'     => $verifyMessage,
                              'userMessage' => $this->getUserVerifyMessage($verifyMessage)
                ];
        
            }elseif(mb_strpos($part, 'ErrorCode:') !== false){
    
                $errorCodes[] = $this->getErrorCode($part);
    
            }elseif(mb_strpos($part, 'Error: The parameter is incorrect.') !== false){
                
                continue; // Ошибку пропускаем, т.к. дальше отловится ее ErrorCode
            }else{
                // В данную ветку ничего не может попасть, т.к. блоки Signer и ErrorCode обрабатываются выше
                throw new Exception("Неизвестное сообщение: '{$part}'");
            }
        }
    
        if(count($errorCodes) != 1){
            throw new Exception('Получено некорректное количетство блоков ErrorCode');
        }
    
        $validateResult['singers'] = $signers;
        $validateResult['errorCode'] = $errorCodes[0];
        
        
        
        
        var_dump($message);
        var_dump($validateResult);
        
        
        
        //$this->parseMessage($message);
        //var_dump($messageParts);
        echo '--------------------------------------------';
    }
    
    
    
    
    
    
    
    
   
    
    
    
}

// [ErrorCode: 0x00000000] - все хорошо
// [ErrorCode: 0x00000057] - загружена открепленная подпись вместо встроенной
//   Error: The parameter is incorrect.
// [ErrorCode: 0x200001f9] - срок действия сертификата истек или еще не наступил
//  Trust for this certificate or one of the certificates in the certificate chain has been revoked.
//  This certificate or one of the certificates in the certificate chain is not time valid.
//    Error: Signature.
/*
[ErrorCode: 0x20000070] - незивестная ошибка
//  Возможно не существует указанный файл

 *
 */


