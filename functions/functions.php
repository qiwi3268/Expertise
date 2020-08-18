<?php


// Предназначен для проверки наличия требуемых параметров в POST запросе
// Принимает параметры-----------------------------------
// params string: перечисление необходимых параметров
// Возвращает параметры-----------------------------------
// true : все принятые параметры присутствуют в массиве POST (на первом уровне вложенности)
// false : в противном случае
//
function checkParamsPOST(string ...$params):bool {

    foreach($params as $param){
        if(!isset($_POST[$param])){
            return false;
        }
    }
    return true;
}
function checkParamsGET(string ...$params):bool {

    foreach($params as $param){
        if(!isset($_GET[$param])){
            return false;
        }
    }
    return true;
}




// Предназначен для очистки массива от html тегов (на первых двух уровнях вложенности)
// Принимает параметры-----------------------------------
// arr array: массив для очистки
// Возвращает параметры-----------------------------------
// array : очищенный массив
//
function clearHtmlArr(array $arr):array {

    $clearArr = [];

    // key1 value1 - первый уровень вложенности
    foreach($arr as $key1 => $value1){

        if(is_array($value1)){

            $tmpArr = [];
            // key2 value2 - второй уровень вложенности
            foreach($value1 as $key2 => $value2){

                if(!is_array($value2)){
                    // ENT_NOQUOTES - оставляет без изменений одинарные и двойные кавычки
                    $tmpArr[$key2] = htmlspecialchars(strip_tags($value2), ENT_NOQUOTES);
                }
            }
            $clearArr[$key1] = $tmpArr;
        }else{

            $clearArr[$key1] = htmlspecialchars(strip_tags($value1), ENT_NOQUOTES);
        }
    }

    return $clearArr;
}



//----------------------------------
//Возвращает: Фамилия Имя Отчетство / Фамилия И.О.
//Принимает:  $userAssoc - ассоциативный массив, в котором необходимо присутствие ключей:
//                'last_name', 'middle_name', 'first_name'
//            $fullFio - ключ со значениями:
//                true - функция вернет полный вариант ФИО
//                false - функция вернет короткий вариант ФИО
//----------------------------------
function GetUserFIO(array $userAssoc, bool $fullFio = true):string {

    if($fullFio){

        return $userAssoc['last_name'].' '.$userAssoc['first_name'].' '.$userAssoc['middle_name'];
    }else{

        $I = mb_substr($userAssoc['first_name'], 0, 1);
        $O = mb_substr($userAssoc['middle_name'], 0, 1);
        return $userAssoc['last_name'].' '.$I.'.'.$O.'.';
    }
}

function GetDdMmYyyyDate(int $timestamp):string {
    return date('d.m.Y', $timestamp);
}

// Предназначен для перезаписи дат в ассоциативном массиве из timestamp в 'dd.mm.yyyy'
// Принимает параметры-----------------------------------
// &assocArray       array : ссылка на ассоциативный массив
// datePropertyNames string: перечисление названий свойств, в которых находятся даты в формате timestamp
//
function UpdateDatesTimestampToDdMmYyyy(array &$assocArray, string ...$datePropertyNames):void {
    
    foreach($datePropertyNames as $propertyName){
        
        $timeStamp = $assocArray[$propertyName];
        
        if(is_numeric($timeStamp)){
            $assocArray[$propertyName] = date('d.m.Y', $timeStamp);
        }
    }
}


function contains(string $haystack, string ...$needles):bool {
    foreach($needles as $needle){
        if(mb_strpos($haystack, $needle) === false) return false;
    }
    return true;
}

function icontains(string $haystack, string ...$needles):bool {
    foreach($needles as $needle){
        if(mb_stripos($haystack, $needle) === false) return false;
    }
    return true;
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
// PregMatchException : во время выполнения функции произошла ошибка или нет вхождений шаблона
//
function GetHandlePregMatch(string $pattern, string $subject, bool $is_preg_match_all):array {
    
    $functionName = $is_preg_match_all ? 'preg_match_all' : 'preg_match';
    $matches = null;
    $result = $functionName($pattern, $subject, $matches);
    
    // Во время выполнения произошли ошибки или нет вхождений шаблона
    if($result === false || $result === 0){
        throw new PregMatchException("Во время выполнения функции: '{$functionName}' произошла ошибка или нет вхождений шаблона: '{$pattern}' в строку: '{$subject}'", 1);
    }
    
    return $matches;
}

function GetHashArray(array $array):array {
    $result = [];
    foreach($array as $elem){
        $result[$elem] = true;
    }
    return $result;
}


// Предназначена для вывода var_dump только у тех пользователей, где в
// get-параметре присутствует debug=1
//
function p($arg){
    if(isset($_GET['debug']) && $_GET['debug'] == 1){
        var_dump($arg);
    }
}
