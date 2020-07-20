<?php


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
    // Принимает параметры-----------------------------------
    // container array : контейнер для хранения значений
    // key      string : ключ массива
    //
    private function checkIssetVariable(array $container, string $key):void {
        if($this->isHardMode && !isset($container[$key])){
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