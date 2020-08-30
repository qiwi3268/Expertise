<?php


namespace Classes;


// Singleton-класс предназначеный для передачи переменных между блоками
//
class VariableTransfer{

    // Сущность (единственный возможный экземпляр) класса
    private static VariableTransfer $instance;
    // Режим работы класса
    // isHardMode  true : жеский режим работы - попытки получить от get'тера несуществующий ключ вызывают exception
    //            false : мягкий режим работы - попытки получить от get'тера несуществующий ключ возвращают null
    private bool $isHardMode;


    // Контейнеры для хранения
    // Флаги существования значений
    private array $existenceFlags = [];
    // Значения
    private array $values = [];

    private function __construct(){
        
        $this->isHardMode = true;
    }


    // Предназначен для получения сущности класса
    // Возвращает параметры----------------------------------
    // VariableTransfer : сущность класса
    //
    static public function getInstance():VariableTransfer {

        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }


    // Предназначен для установки жесткого режима работы
    //
    public function setHardMode():void {
        $this->isHardMode = true;
    }


    // Предназначен для установки мягкого режима работы
    //
    public function setSoftMode():void {
        $this->isHardMode = false;
    }


    // Предназначен для проверки существования ключа в указанном массиве при жестком режиме работы
    // Возможно переопределить текущий режим работы для конкретного вызова, указав в ключе:
    // %S(soft) или %H(hard)
    // Принимает параметры-----------------------------------
    // container  array : контейнер для хранения значений
    // &key      string : ключ массива. Из значения будет вырезан режим работы, если он указан
    //
    private function checkIssetVariable(array $container, string &$key):void {
    
        $isHardMode = $this->isHardMode;
        
        if(contains($key, '%S')){
            $isHardMode = false;
            $key = str_replace('%S', '', $key);
        }elseif(contains($key, '%H')){
            $isHardMode = true;
            $key = str_replace('%H', '', $key);
        }
        
        if($isHardMode && !isset($container[$key])){
            throw new Exception("Ключ '$key' не существует в запрашиваемом контейнере");
        }
    }


    // Блок установки/получения флагов существования
    //
    public function setExistenceFlag(string $key, bool $value):void {
        $this->existenceFlags[$key] = $value;
    }
    public function getExistenceFlag(string $key):?bool {
        $this->checkIssetVariable($this->existenceFlags, $key);
        return $this->existenceFlags[$key] ?? null;
    }


    // Блок установки/получения значений
    //
    public function setValue(string $key, $value):void {
        $this->values[$key] = $value;
    }
    public function getValue(string $key){
        $this->checkIssetVariable($this->values, $key);
        return $this->values[$key] ?? null;
    }
}