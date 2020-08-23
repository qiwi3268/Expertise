<?php


//todo переименовать в MiscHandler
// Предназначен для валидации независимого справочника
class SingleMiscValidator{

    
    //private bool $exist;      // Флаг наличия введенных данных
    private string $form_value; // int'овое значение из формы
    private string $class;      // Название класса справочника
    
    private const single_interface = 'Interface_singleMiscTableValidate';
    private const single_method = 'checkExistById';
    
    private const dependent_interface = 'Interface_dependentMiscTableValidate';
    private const dependent_method = 'checkExistCORRByIds';
    
    
    
    // form_value string : значение из формы
    // class      string : название класса справочника
    public function __construct(string $form_value, string $class){
        
        $this->form_value = $form_value;
        $this->class = $class;
        
        if($form_value !== ''){
            
            // Проверка на целочисленное значение
            if(($int_value = filter_var($form_value, FILTER_VALIDATE_INT)) === false){
                throw new Exception("Передано некорректное значение справочника: '{$form_value}'", 4);
            }
            
            // Проверка на существование указанного класса
            if(!class_exists($class)){
                throw new Exception("Класс: '{$class}' не существует");
            }
    
            $interfaces = class_implements($class);
            
    
            // Проверка на реализацию интерфейса  в нужном классе
            if(!$interfaces || !in_array(self::INTERFACE, $interfaces, true)){
                throw new ApplicationFormHandlerException("Класс: '{$class}' не реализует интерфейс: '".self::INTERFACE."'");
            }
    
            if($isSingle){
                $interface = self::TYPE['single']['interface'];
                $method = self::TYPE['single']['method'];
                $params = [$int_value];
            }else{
                $interface = self::TYPE['dependent']['interface'];
                $method = self::TYPE['dependent']['method'];
                $params = [$int_valueMain, $int_value];
            }
    
            // Запрашиваемое значение справочника не существует
            if(!call_user_func_array([$class, $method], $params)){
                throw new Exception("Запрашиваемое значение справочника: '{$int_value}' не существует в таблице класса: '{$class}'");
            }
            
            $this->exist = true;
            $this->int_value = $int_value;
            $this->class = $class;
        }else{
            $this->exist = false;
        }
    }
    
    public function singleValidate(){
    
    }
    public function dependentValidate(int $int_valueMain){
    
    }
    
    
    // columnName   string : имя столбца в БД, оно же имя ключа в ассоциативном массиве
    //todo убрать
    public function addToUpdate(string $columnName):void {
        
        $val = $this->exist ? $this->int_value : '';
        DataToUpdate::add($val, $columnName);
    }
    
}
