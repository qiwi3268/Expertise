<?php


namespace MiscValidator;

// Абстрактный класс, предназначенный для валидации справочников. Предоставляет потомкам (SingleMisc и DependentMisc)
// инкапсулированные свойства и интерфейс (методы) для валидации
abstract class Validator{
    
    protected string $form_value;     // Значение из формы
    protected string $class;          // Название класса справочника
    
    protected ?int $int_value = null; // Полученное методом getValidatedInt int'овое значение справочника
    protected ?bool $isExist = null;  // Флаг наличия проверенных введенных данных справочника
    
    
    
    // Предназначен для получения проверенного значения справочника из формы
    // Принимает параметры-----------------------------------
    // form_value string : значение из формы
    // Возвращает параметры----------------------------------
    // int : преобразованное к int'у значение справочника
    // Выбрасывает исключения--------------------------------
    // MiscValidatorException :
    // code:
    //  1 - передано некорректное значение справочника
    //
    protected function getValidatedInt(string $form_value):int {
        
        if(($int_value = filter_var($form_value, FILTER_VALIDATE_INT)) === false){
            throw new \MiscValidatorException("Передано некорректное значение справочника: '{$form_value}'", 1);
        }
        return $int_value;
    }
    
    
    // Предназначен для проверки существования указанного класса и реализацию нужного интерфейса
    // Принимает параметры-----------------------------------
    // class     string : название класса справочника
    // interface string : требуемый для реализации интерфейс
    // Выбрасывает исключения--------------------------------
    // MiscValidatorException :
    // code:
    //  2 - класс не сущетвует
    //  3 - класс не реализует интерфейс
    //
    protected function checkClass(string $class, string $interface):void {
        
        if(!class_exists($class)){
            throw new \MiscValidatorException("Класс справочника: '{$class}' не существует", 2);
        }
        
        $interfaces = class_implements($class);
        
        if(!$interfaces || !in_array($interface, $interfaces, true)){
            throw new \MiscValidatorException("Класс: '{$class}' не реализует интерфейс: '{$interface}'", 3);
        }
    }
    
    
    // Предназначен для получения существования запрашиваемого класса
    // Принимает параметры-----------------------------------
    // class  string : название класса справочника
    // method string : метод для проверки существования класса
    // params  array : параметры запроса
    // Выбрасывает исключения--------------------------------
    // MiscValidatorException :
    // code:
    //  4 - запрашиваемое значение справочника не существует
    //
    protected function checkMiscExist(string $class, string $method, array $params):void {
        
        // Произошла ошибка при вызове функции или метод вернул отрицательный результат
        if(!call_user_func_array([$class, $method], $params)){
            $params = implode(', ', $params);
            throw new \MiscValidatorException("Запрашиваемое значение справочника с параметрами запроса: '{$params}' не существует в таблице класса: '{$class}'", 4);
        }
    }
    
    
    // Предназначен для получения флага наличия проверенных введенных данных справочника
    // Возвращает параметры----------------------------------
    // bool : флаг наличия проверенных введенных данных справочника
    // Выбрасывает исключения--------------------------------
    // LogicException : попытка вызвать метод перед валидацией спраовчника
    //
    public function isExist():bool {
        if(is_null($this->isExist)){
            throw new \LogicException("Попытка вызвать метод \MiscValidator\Validator::isExist при значении свойства isExist = null. Название класса справочника: '{$this->class}'");
        }
        return $this->isExist;
    }
    
    
    // Предназначен для получения int'ового значения справочника
    // Возвращает параметры----------------------------------
    // int : флаг наличия проверенных введенных данных справочника
    // Выбрасывает исключения--------------------------------
    // LogicException : попытка вызвать метод перед валидацией спраовчника
    //
    public function getIntValue():int {
        if(is_null($this->int_value)){
            throw new \LogicException("Попытка вызвать метод \MiscValidator\Validator::getIntValue при значении свойства int_value = null. Название класса справочника: '{$this->class}'");
        }
        return $this->int_value;
    }
    
    
    // Предназначен для комплексной проверки справочника
    // *** Возвращаемый тип не объявлен, чтобы дочерние классы при желании могли реализовать цепочки вызовов
    //
    abstract public function validate();
}
