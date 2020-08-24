<?php


// Предназначен для валидации (получения) примитивов
class PrimitiveValidator{
    
    
    // Предназначен для получения массива, полученного декодированием входной json-строки, массив которого является
    // индексным массивом (без вложенных массивов) с только числовыми значениями
    // Принимает параметры-----------------------------------
    // json string : входной json
    // checkSame   : bool требуется ли проверка на наличие одинаковых значений
    // Возвращает параметры----------------------------------
    // array : декодированный массив из json-строки
    // Выбрасывает исключения--------------------------------
    // PrimitiveValidatorException :
    // code:
    //  1 - ошибка при декодировании json-строки
    //  2 - декодированная json-строка не является массивом
    //  3 - в массиве, полученном из json-строки, присутствует нечисловой элемент
    //  4 - в массиве, полученном из json-строки элемент найден более одного раза
    //
    public function getValidatedArrayFromNumericalJson(string $json, bool $checkSame):array {
        
        try{
            
            $array = json_decode($json, false, 2, JSON_THROW_ON_ERROR);
        }catch(jsonException $e){
            
            $msg = "jsonException message: '{$e->getMessage()}', code: '{$e->getCode()}'";
            throw new PrimitiveValidatorException($msg, 1);
        }
        
        if(!is_array($array)){
            throw new PrimitiveValidatorException("Декодированная json-строка: '{$json}' не является массивом", 2);
        }
        // Проверка массива на нечисловые значения
        foreach($array as &$element){
            
            if(($int = filter_var($element, FILTER_VALIDATE_INT)) === false){
                throw new PrimitiveValidatorException("В массиве, полученном из json-строки, присутствует нечисловой элемент: '{$element}'", 3);
            }
            $element = $int;
        }
        unset($element);
        
        // Проверка массива на одинаковые значения
        if($checkSame){
            
            foreach(array_count_values($array) as $element => $count){
                
                if($count > 1) throw new PrimitiveValidatorException("В массиве, полученном из json-строки, элемент: '{$element}' найден: '{$count}' раз(а)", 4);
            }
        }
        return $array;
    }
    
    
    // Предназначен для валидации строковой даты формата "дд.мм.гггг"
    // Принимает параметры-----------------------------------
    // fullDate string : дата формата "дд.мм.гггг"
    // Выбрасывает исключения--------------------------------
    // PrimitiveValidatorException :
    // code:
    //  5 - строковая дата является некорректной
    //  6 - дата не существует по григорианскому календарю
    //
    public function validateStringDate(string $fullDate):void {
        
        // начало текста
        // 1 группа:
        //    любая цифра 2 раза
        // точка
        // 2 группа:
        //    любая цифра 2 раза
        // точка
        // 3 группа:
        //    любая цифра 4 раза
        // конец текста
        $pattern = "/\A(\d{2})\.(\d{2})\.(\d{4})\z/";
        try{
            list(1 => $date, 2 => $month, 3 => $year) =  GetHandlePregMatch($pattern, $fullDate, false);
        }catch(PregMatchException $e){
            throw new PrimitiveValidatorException("Строковая дата: '{$fullDate}' является некорректной", 4);
        }
        
        if(!checkdate($month, $date, $year)) throw new PrimitiveValidatorException("Дата: '{$fullDate}' не существует по григорианскому календарю", 5);
    }
}
