<?php


namespace csp;


class MessageParser{
    
    // Код, соответствующий успешному выполнению команды
    public const ok_error_code = '0x00000000';
    
    // Хэш-массив популярных имен из БД
    private array $hashNames;
    
    
    // Принимает параметры-----------------------------------
    // needNames bool: флаг необходимости инициализировать массив имен. Не нужен, если класс
    //                 используется не для получения ФИО
    //
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
            
            if(!icontains($part, 'CryptCP 4.0 (c) "Crypto-Pro", 2002-2020.') &&
                !icontains($part, 'CryptCP 5.0 (c) "Crypto-Pro", 2002-2019.') &&
                !icontains($part, 'Command prompt Utility for file signature and encryption.') &&
                !icontains($part, 'Folder') &&
                !icontains($part, 'Signature verifying...') &&
                !icontains($part, 'CSPbuild') &&
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
        
        // запятая ноль и более раз                 | если ФИО начинает строку
        // пробельный символ ноль и более раз       | если ФИО начинает строку или просто нет пробела после запятой
        // любой символ кириллицы один и более раз  | Фамилия
        // запятая
        // пробельный символ ноль и более раз       | просто нет пробела после запятой
        // любой символ кириллицы один и более раз  | Имя
        // пробельный символ
        // любой символ кириллицы один и более раз  | Отчество
        // запятая ноль и более раз                 | если ФИО завершает строку
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/,*\s*[а-яё]+,\s*[а-яё]+\s[а-яё]+,*/iu';
    
        $matches = GetHandlePregMatch($pattern, $Signer, true)[0]; // Массив полных вхождений шаблона
        
        $count = 0;             // Количество найденных ФИО
        $FIOs = [];             // Массив с фамилиями, именами и отчествами для вывода exception'а
    
        // Получаем слова
        // любой символ кириллицы один и более раз
        // - регистронезависимые
        // - использование кодировки utf-8
        $fio_pattern = '/[а-яё]+/iu';
        
        foreach($matches as $match){
    
            $fio_matches = GetHandlePregMatch($fio_pattern, $match, true)[0]; // Массив полных вхождений шаблона
            
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
        if($count === 0) throw new \CSPMessageParserException("В БД не нашлось имени из ФИО: '{$FIOs}'", 1);
        
        // В одном Signer нашлось больше одного ФИО
        if($count > 1) throw new \CSPMessageParserException("В одном Signer: '{$Signer}' нашлось больше одного ФИО: '{$FIOs}'", 2);
        
        return $result;
    }
    
    
    // Предназначен для получения данных о сертификате из строки вида - 'Signer: ...'
    // Принимает параметры-----------------------------------
    // Signer string: строка с подписантом
    // Возвращает параметры----------------------------------
    // string : данные сертификата
    //
    public function getCertificateInfo(string $Signer):string {
        
        // Signer:
        // пробельный символ ноль и более раз
        // 1 группа:
        //    любой символ один и более раз
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/Signer:\s*(.+)/iu';
        return GetHandlePregMatch($pattern, $Signer, false)[1]; // Возвращаем результат первой группы
    }
    
    
    // Предназначен для получения кода ошибки из в
    // Принимает параметры-----------------------------------
    // message string: вывод исполняемой cryptcp команды
    // Возвращает параметры----------------------------------
    // string : код ошибки
    //
    public function getErrorCode(string $message):string {
        
        // [ErrorCode:
        // пробельный символ ноль и более раз
        // 1 группа:
        //    любой символ один и более раз
        // ]
        // - регистронезависимых
        // - использование кодировки utf-8
        $pattern = '/\[ErrorCode:\s*(.+)]/iu';
        return GetHandlePregMatch($pattern, $message, false)[1]; // Возвращаем результат первой группы
    }
}
