<?php


class It implements Iterator{
    
    private int $currentKey;
    private array $array;
    private array $arrayKeys;
    
    public function __construct(array $array){
        $this->array = $array;
        $this->arrayKeys = array_keys($array);
        $this->rewind();
    }
    
    // Возврат текущего элемента
    public function current(){
        $key = $this->arrayKeys[$this->currentKey];
        return $this->array[$key];
    }
    
    // Возврат ключа текущего элемента
    public function key(){
        return $this->arrayKeys[$this->currentKey];
    }
    
    // Переход к следующему элементу
    public function next():void {
        ++$this->currentKey;
    }
    
    // Перемотка итератора на первый элемент
    public function rewind():void {
        $this->currentKey = 0;
    }
    
    // Проверяет корректность текущей позиции
    public function valid():bool {
        return isset($this->arrayKeys[$this->currentKey]);
    }
    
    // Изменяет значение текущего элемента
    public function changeCurrent($value):void {
        $key = $this->arrayKeys[$this->currentKey];
        $this->array[$key] = $value;
    }
    
    public function deleteByKey($key){
        if(isset($this->arrayKeys[$key])){
            unset()
        }else{
            // warning
        }
    }
}

$array = ['hey'=>'a', 'b', 'c', 'd', 'e'];
$it = new It($array);

foreach($it as $value){
   
    $it->changeCurrent('bb');
    
    var_dump($value);
    var_dump($it->current());
    
}
var_dump($it);