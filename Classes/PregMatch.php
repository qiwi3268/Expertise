<?php


class PregMatchHelper{
    
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
    public function getHandlePregMatch(string $pattern, string $subject, bool $is_preg_match_all):array {
        
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
