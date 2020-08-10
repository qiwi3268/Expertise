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
        
        $this->parseMessage($message);
        var_dump($message);
        echo '--------------------------------------------';
    }
    
    private function parseMessage(string $message){
        
        
        $pos_Signer = mb_strpos($message, 'Signer:');
        
        
        if($pos_Signer !== false){
            $posEOL_Signer = mb_strpos($message, PHP_EOL, $pos_Signer);
            
            if($posEOL_Signer === false){
                // все плохо
            }
            $start = $pos_Signer + 8;
            $length = $posEOL_Signer - $pos_Signer - 8;
            $Signer = mb_substr($message, $start, $length);
            
            $FIO = $this->getFIO($Signer);
            var_dump($FIO);
        }else{
            echo 'Отсутствует SIGNER'; //todo выход
        }
    }
    
    
    
    private function getErrorCode(string $message){
    
    }
    
    
    private function getFIO(string $Signer){
    
        // запятая (может и не быть)            | если ФИО начинает строку
        // пробел (может и не быть)             | если ФИО начинает строку или просто нет пробела после запятой
        // слово из одного и более символов     | Фамилия
        // запятая
        // пробел (может и не быть)             | просто нет пробела после запятой
        // слово из одного и более символов     | Имя
        // пробел
        // слово из одного и более символов     | Отчество
        // запятая (может и не быть)            | если ФИО завершает строку
        // - регистронезависимых
        // - использование кодировки utf-8
        $pattern = '/,\s*[а-яё]+,\s*[а-яё]+\s[а-яё]+,*/iu';
    
        $result = preg_match_all($pattern, $Signer, $matches);
        
        // Ошибка в регулярном выражении или нет вхождений ФИО
        if($result === false || $result === 0){
            echo 'ОШИБКА 1'; //todo выход
        }
        
        $matches = $matches[0]; // Массив полных вхождений шаблона
        $count = 0;             // Количество найденных ФИО
        
        foreach($matches as $match){
            
            // Получаем слова
            preg_match_all('/[а-яё]+/iu', $match, $fio_matches);
    
            // Так как нет уверенности в том, что имя следует именно вторым, поэтому проверяю все
            foreach($fio_matches[0] as $part){
                if(isset($this->hashNames[$part])){
                    $count++;
                    break;
                }
            }
        }
        
        if($count == 0){
            echo 'ОШИБКА 2'; //todo выход (В БД не нашлось подходящего имени)
        }
        if($count > 1){
            echo 'ОШИБКА 3'; //todo выход
        }
        return implode(' ', $fio_matches[0]);
    }
    
    private function getErrorMessage($errorCode){
        $pattern = '/\[errorcode:\s*(.+)]/i';
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
 * добиться вывода ошибки в скрипт
 * /opt/cprocsp/bin/amd64/cryptcp -verify -mca -all -errchain -verall /var/www/internal/истек серт но был годен (1).pdf.sig 2>&1
 *
 */