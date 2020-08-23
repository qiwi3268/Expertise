<?php

class DataToUpdate{
    
    static private ?array $flatAssoc = null;
    static private array $dataToUpdate = [];
    
    private function __construct(){}
    
    static public function setFlatAssoc(array $assoc){
        self::$flatAssoc = $assoc;
    }
    
    
    // Предназначен для добавления нового значения formValue для столбца columnName
    // в общий список данных, которым нужен update
    // Принимает параметры-----------------------------------
    // formValue    string : значение из переданной формы (всегда строка)
    // columnName   string : имя столбца в БД, оно же имя ключа в ассоциативном массиве
    // &dataToUpdate array : ссылка на массив для сохранения данных, которым нужно сделать update
    //
    // * formValue false (например, при передаче через функцию strtotime) корректно работает, т.к. преобразование
    //   (string) false === ''
    //
    static public function add(string $value, string $columnName) {
        
        //todo проверка что уже был установлен flatAssoc
        // Из формы пришло пустое значение
        if($value === ''){
        
            // В БД было что-то записано (пользователь удалил информацию)
            if(!is_null(self::$flatAssoc[$columnName])){
                self::$dataToUpdate[$columnName] = null;
            }
            
        // Из формы пришло значение
        }else{
        
            // Если поле в БД представлено числом (например, дата или справочник), то необходимо преобразовать его
            // к int'у, т.к. далее следует жесткое сравнение
            if(is_int(self::$flatAssoc[$columnName])){
                $value = (int)$value;
            }
        
            // Пользователь отправил данные, отличающиеся от записи в БД
            // Жесткое сравнение необходимо, чтобы отличать введенный 0 и NULL из БД и т.д.
            if($value !== self::$flatAssoc[$columnName]){
                self::$dataToUpdate[$columnName] = $value;
            }
        }
    
        // Пользователь отправил пустое значение, а БД - NULL : не обновляем данные
        // Пользователь отправил значение, такое же, как в БД : не обновляем данные
    }
    
    
    public function isEmpty():bool {
        return empty(self::$dataToUpdate) ? true : false;
    }
    public function get():array {
        return self::$dataToUpdate;
    }
}
