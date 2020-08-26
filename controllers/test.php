<?php



/*
 * $id = 10;

$test = new \core\Classes\Transaction();

$test->add('ApplicationsTable', 'createTemporary', [1, '9999-9990']);
$test->add('ApplicationsTable', 'createTemporary', [1, '9999-9992']);
$test->add('ApplicationsTable', 'createTemporary', [1, '9999-9992']);
$test->add('ApplicationsTable', 'createTemporary', [5, '9999-9992']);


$results = $test->start()->lastResults;
*/


$test = is_numeric('42a');


$array =  ['type'       => null,
           'is_changed' => "1",
           'no_data'    => 0,
           'percent'    => 200];


$settings = ['type'       => ['is_int', 'is_null'],
             'is_changed' => ['is_numeric'],
             'percent'    => [[new PrimitiveValidator(), 'validatePercent']]
    ];

validateAssociativeArray($array, $settings);







// settings array: ключ - элемент (ключ из array), который обязательно должен присутствовать в массиве. Значение - массив с callback'ами для проверки
// Если callback строка - функция для проверки
//               массив - [0 => экземпляр объекта или имя класса, 1 => имя метода]
function validateAssociativeArray(array $array, array $settings):void {

    foreach($settings as $key => $callbacks){
        
        if(!array_key_exists($key, $array)){
            
            throw new PrimitiveValidatorException("Во входном массиве отсутствует обязательное поле: '{$key}'");
        }
        
        $result = false;
        
        // Хотя бы один callback должен вернуть true
        foreach($callbacks as $callback){
    
            // Проверка на существование принятого callback'а
            if(is_array($callback)){
            
                if(!method_exists($callback[0], $callback[1])) throw new BadMethodCallException("Переданный метод: '{$callback[1]} не существует'");
            
            }elseif(!function_exists($callback)) throw new BadFunctionCallException("Переданная функция: '{$callback}' не существует");
            
            $res = call_user_func($callback, $array[$key]);
            // Строгое равенство, т.к. callback может ничего не возвращать (null)
            if($res === true || is_null($res)){
                $result = true;
                break 1;
            }
        }
        
        if(!$result){
            throw new PrimitiveValidatorException("Значение входного массива по ключу: '{$key}' не прошло проверку");
        }
    }
}

