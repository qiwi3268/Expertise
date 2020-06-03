<?php


class VariablesToView{

    private static VariablesToView $instance;

    // Флаги существования каких-либо значений
    private array $existenceFlags = [];

    // Значения
    private array $values = [];

    private function __construct(){}

    static public function getInstance():VariablesToView {

        if(empty(self::$instance)){
            self::$instance = new VariablesToView();
        }
        return self::$instance;
    }


    public function setExistenceFlag(string $key, bool $value):void {
        $this->existenceFlags[$key] = $value;
    }
    public function getExistenceFlag(string $key):?bool {
        return $this->existenceFlags[$key] ?? null;
    }


    public function setValue(string $key, $value):void {
        $this->values[$key] = $value;
    }
    public function getValue(string $key){
        return $this->values[$key] ?? null;
    }
}