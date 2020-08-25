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
            throw new PrimitiveValidatorException("Строковая дата: '{$fullDate}' является некорректной", 5);
        }
        
        if(!checkdate($month, $date, $year)) throw new PrimitiveValidatorException("Дата: '{$fullDate}' не существует по григорианскому календарю", 6);
    }
    
    
    // Предназначен для валидации ИНН
    // Принимает параметры-----------------------------------
    // INN string : ИНН (12 цифр для физ.лиц и 10 цифр для юр.лиц)
    // Выбрасывает исключения--------------------------------
    // PrimitiveValidatorException :
    // code:
    //  7 - введенный ИНН является некорректным
    public function validateINN(string $INN):void {
        
        // начало текста
        // любая цифра 10 раз
        // конец текста
        // ИЛИ
        // начало текста
        // любая цифра 12 раз
        // конец текста
        $pattern = "/\A\d{10}\z|\A\d{12}\z/";
        try{
            GetHandlePregMatch($pattern, $INN, false);
        }catch(PregMatchException $e){
            throw new PrimitiveValidatorException("Введенный ИНН: '{$INN}' является некорректным", 7);
        }
    }
    
    
    // Предназначен для валидации КПП
    // Принимает параметры-----------------------------------
    // KPP string : КПП (9 цифр)
    // Выбрасывает исключения--------------------------------
    // PrimitiveValidatorException :
    // code:
    //  8 - введенный КПП является некорректным
    public function validateKPP(string $KPP):void {
    
        // начало текста
        // любая цифра 9 раз
        // конец текста
        $pattern = "/\A\d{9}\z/";
        try{
            GetHandlePregMatch($pattern, $KPP, false);
        }catch(PregMatchException $e){
            throw new PrimitiveValidatorException("Введенный КПП: '{$KPP}' является некорректным", 8);
        }
    }
    
    
    // Предназначен для валидации ОГРН
    // Принимает параметры-----------------------------------
    // OGRN string : ОГРН (13 цифр)
    // Выбрасывает исключения--------------------------------
    // PrimitiveValidatorException :
    // code:
    //  9 - введенный ОГРН является некорректным
    public function validateOGRN(string $OGRN):void {
    
        // начало текста
        // любая цифра 13 раз
        // конец текста
        $pattern = "/\A\d{13}\z/";
        try{
            GetHandlePregMatch($pattern, $OGRN, false);
        }catch(PregMatchException $e){
            throw new PrimitiveValidatorException("Введенный ОГРН: '{$OGRN}' является некорректным", 9);
        }
    }
    
    
    // Предназначен для валидации email
    // Принимает параметры-----------------------------------
    // email string : email
    // Выбрасывает исключения--------------------------------
    // PrimitiveValidatorException :
    // code:
    // 10 - введенный email является некорректным
    public function validateEmail(string $email):void {
        if((filter_var($email, FILTER_VALIDATE_EMAIL)) === false){
            throw new PrimitiveValidatorException("Введенный email: '{$email}' является некорректным", 10);
        }
    }
    
    
    // Предназначен для валидации процента
    // Принимает параметры-----------------------------------
    // percent string : процент [0:100]
    // Выбрасывает исключения--------------------------------
    // PrimitiveValidatorException :
    // code:
    // 11 - введенный процент является некорректным
    public function validatePercent(string $percent):void {
        $options = ['options' => ['min_range' => 0,
                                  'max_range' => 100]
        ];
        if((filter_var($percent, FILTER_VALIDATE_INT, $options)) === false){
            throw new PrimitiveValidatorException("Введенный процент: '{$percent}' является некорректным", 11);
        }
    }
}
