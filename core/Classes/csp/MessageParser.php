<?php


namespace csp;


class MessageParser{
    
    // Хэш-массив популярных имен из БД
    private array $hashNames;
    
    
    public function __construct(bool $needNames){
        
        if($needNames){
            $names = \PeopleNameTable::getNames();
            // Перевод выборки в формат хэш-массива
            foreach($names as $name) $this->hashNames[$name] = true;
        }
    }
    
    
    // Предназначен для получения сообщения без технической его части:
    //      CryptCP 4.0 (c) "Crypto-Pro", 2002-2020.
    //      Command prompt Utility for File signature and encryption.
    //      Folder '/var/www...'
    //      Signature verifying...
    //      ../../../../CSPbuild/CSP/samples/CPCrypt/DSign.cpp
    // Принимает параметры-----------------------------------
    // message string: вывод исполняемой команды по валидации подписи
    // Возвращает параметры----------------------------------
    // array : массив частей сообщения без технической части, разбитый по символам-переносам строк
    //
    public function getMessagePartsWithoutTechnicalPart(string $message):array {
        
        $result = [];
        
        $parts = explode(PHP_EOL, $message);
        
        foreach($parts as $part){
            
            if(mb_strpos($part, 'CryptCP 4.0 (c) "Crypto-Pro", 2002-2020.') === false &&
                mb_strpos($part, 'Command prompt Utility for File signature and encryption.') === false &&
                mb_strpos($part, 'Folder') === false &&
                mb_strpos($part, 'Signature verifying...') === false &&
                mb_strpos($part, 'CSPbuild') === false &&
                $part !== ''){
                
                $result[] = trim($part); // Удаляем пробельные символы вначале и вконце строки
            }
        }
        return $result;
    }
    
    
    // Предназначен для получения ФИО из строки вида - 'Signer: ...'
    // Принимает параметры-----------------------------------
    // Signer string: строка с подписантом
    // Возвращает параметры----------------------------------
    // string : ФИО подписанта
    // Выбрасывает исключения--------------------------------
    // CSPMessageParserException : в БД не нашлось имени из ФИО / в одном Signer нашлось больше одного ФИО
    //
    public function getFIO(string $Signer):string {
        
        // запятая (может и не быть)            | если ФИО начинает строку
        // пробел (может и не быть)             | если ФИО начинает строку или просто нет пробела после запятой
        // слово из одного и более символов     | Фамилия
        // запятая
        // пробел (может и не быть)             | просто нет пробела после запятой
        // слово из одного и более символов     | Имя
        // пробел
        // слово из одного и более символов     | Отчество
        // запятая (может и не быть)            | если ФИО завершает строку
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/,\s*[а-яё]+,\s*[а-яё]+\s[а-яё]+,*/iu';
    
        $matches = $this->getHandlePregMatch($pattern, $Signer, true)[0]; // Массив полных вхождений шаблона
        
        $count = 0;             // Количество найденных ФИО
        $FIOs = [];             // Массив с фамилиями, именами и отчествами для вывода exception'а
    
        // Получаем слова
        // слово из одного и более символов
        // - регистронезависимые
        // - использование кодировки utf-8
        $fio_pattern = '/[а-яё]+/iu';
        
        foreach($matches as $match){
    
            $fio_matches = $this->getHandlePregMatch($fio_pattern, $match, true)[0]; // Массив полных вхождений шаблона
            
            // Так как нет уверенности в том, что имя следует именно вторым, поэтому проверяем все слова
            foreach($fio_matches as $part){
                if(isset($this->hashNames[$part])){
                    $result = implode(' ', $fio_matches);
                    $count++;
                    break;
                }
                $FIOs[] = $part;
            }
        }
    
        $FIOs = implode(', ', $FIOs);
        
        // В БД не нашлось подходящего имени
        if($count === 0) throw new \CSPMessageParserException("В БД не нашлось имени из ФИО: '{$FIOs}'", 2);
        
        // В одном Signer нашлось больше одного ФИО
        if($count > 1) throw new \CSPMessageParserException("В одном Signer: '{$Signer}' нашлось больше одного ФИО: '{$FIOs}'", 3);
        
        return $result;
    }
    
    
    // Предназначен для получения данных о сертификате из строки вида - 'Signer: ...'
    // Принимает параметры-----------------------------------
    // Signer string: строка с подписантом
    // Возвращает параметры----------------------------------
    // string : данные сертификата
    //
    public function getCertificateInfo(string $Signer):string {
        
        // от Signer:
        // пробел (может и не быть)
        // слово из одного и более символов (это результат первой группы)
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/Signer:\s*(.+)/iu';
        return $this->getHandlePregMatch($pattern, $Signer, false)[1]; // Возвращаем результат первой группы
    }
    
    
    // Предназначен для получения кода ошибки из строки вида - '[ErrorCode: 0x00000000]'
    // Принимает параметры-----------------------------------
    // ErrorCode string: строка с ошибкой
    // Возвращает параметры----------------------------------
    // string : код ошибки
    //
    public function getErrorCode(string $ErrorCode):string {
        
        // от [ErrorCode:
        // пробел (может и не быть)
        // слово из одного и более символов (это результат первой группы)
        // до ]
        // - регистронезависимых
        // - использование кодировки utf-8
        $pattern = '/\[ErrorCode:\s*(.+)]/iu';
        return $this->getHandlePregMatch($pattern, $ErrorCode, false)[1]; // Возвращаем результат первой группы
    }
    
    
    // Предназначен для получения массива совпавших значений с учетом обработки результата работы функции
    // Результатом работы функции обязательно должно быть вхождение шаблона
    // Принимает параметры-----------------------------------
    // pattern string         : искомый шаблон
    // subject string         : входная строка
    // is_preg_match_all bool : в ходе работы метода будет выполняться функция:
    //      true  - preg_match_all
    //      false - preg_match
    // Возвращает параметры----------------------------------
    // array : массив совпавших значений
    // Выбрасывает исключения--------------------------------
    // CSPMessageParserException : во время выполнения функции произошла ошибка или нет вхождений шаблона
    //
    private function getHandlePregMatch(string $pattern, string $subject, bool $is_preg_match_all):array {
        
        $functionName = $is_preg_match_all ? 'preg_match_all' : 'preg_match';
        $matches = null;
        $result = $functionName($pattern, $subject, $matches);
        
        // Во время выполнения произошли ошибки или нет вхождений шаблона
        if($result === false || $result === 0){
            throw new \CSPMessageParserException("Во время выполнения функции: '{$functionName}' произошла ошибка или нет вхождений шаблона: '{$pattern}' в строку: '{$subject}'", 1);
        }
        
        return $matches;
    }
}
