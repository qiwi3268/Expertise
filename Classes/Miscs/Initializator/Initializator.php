<?php



namespace Classes\Miscs\Initializator;


// Абстрактный класс, предназначеный для инициализации справочников в форме. Предоставляет потомкам
// инкапсулированные свойства и интерфейс (методы) для инициализации
// Потомки в методе-конструкторе должны заполнить через методы setSingleMisc / setDependentMisc
// нужные им справочники
//
abstract class Initializator{
    
    // Массив одиночных справочников
    private array $singleMiscs = [];
    // Массив зависимых справочников
    private array $dependentMiscs = [];
    
    
    // Предназначен для разбивки одиночных справочников по страницам
    // Возвращает параметры----------------------------------
    // array : разбитые постранично справочники
    // Выбрасывает исключения--------------------------------
    // LogicException : вызван метод при пустом массиве singleMiscs
    //
    public function getPaginationSingleMiscs():array {
        
        if(empty($this->singleMiscs)) throw new \LogicException("Вызван метод Initializator::getPaginationSingleMiscs при пустом массиве singleMiscs");
        
        $result = [];
        
        foreach($this->singleMiscs as $miscName => $misc){
            $result[$miscName] = array_chunk($misc, static::PAGINATION_SIZE, false);
        }
        return $result;
    }
    
    
    // Предназначен для разбивки зависимых справочников по страницам
    // Возвращает параметры----------------------------------
    // array : разбитые постранично справочники
    // Выбрасывает исключения--------------------------------
    // LogicException : вызван метод при пустом массиве dependentMiscs
    //
    public function getPaginationDependentMiscs():array {
    
        if(empty($this->dependentMiscs)) throw new \LogicException("Вызван метод Initializator::getPaginationDependentMiscs при пустом массиве dependentMiscs");
    
        $result = [];
        
        foreach($this->dependentMiscs as $miscName => $mainMiscIds){
            
            // Цикл по справочнику в зависимоти от id-главного справочника
            foreach($mainMiscIds as $mainMiscId => $misc){
                
                $result[$miscName][$mainMiscId] = array_chunk($misc, static::PAGINATION_SIZE, false);
            }
        }
        return $result;
    }
    
    
    // Предназначен для установки одиночного справочника
    // Принимает параметры-----------------------------------
    // name string : название справочника
    // misc  array : справочник
    //
    protected function setSingleMisc(string $name, array $misc):void {
        $this->singleMiscs[$name] = $misc;
    }
    
    
    // Предназначен для установки зависимого справочника
    // Принимает параметры-----------------------------------
    // name string : название справочника
    // misc  array : справочник
    //
    protected function setDependentMisc(string $name, array $misc):void {
        $this->dependentMiscs[$name] = $misc;
    }
    
    
    // Предназначен для инициализации справочников через методы setSingleMisc / setDependentMisc
    //
    abstract public function __construct();
}