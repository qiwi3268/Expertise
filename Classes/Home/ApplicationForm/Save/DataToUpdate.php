<?php


// Предназначен для добавления новых данных из анкеты заявления
//
class DataToUpdate{
    
    static private ?array $flatAssoc = null;
    static private array $dataToUpdate = [];
    
    private function __construct(){}
    
    static public function setFlatAssoc(array $assoc){
        self::$flatAssoc = $assoc;
    }
    
    
    // Предназначен для добавления нового значения из формы в общий список данных, которым нужен update
    // *** Поле в БД представлено числом (например, дата или справочник)
    // Принимает параметры-----------------------------------
    // form_value string : значение из переданной формы (всегда строка)
    // columnName string : имя столбца в БД, оно же имя ключа в ассоциативном массиве
    //
    static public function addInt(string $form_value, string $columnName):void {
        
        // Из формы пришло пустое значение и в БД что-то записано (пользователь удалил информацию)
        if($form_value === ''){
            
            if(!is_null(self::$flatAssoc[$columnName])){
                self::$dataToUpdate[$columnName] = null;
            }
    
        // Пользователь отправил данные отличающиеся от записи в БД
        // Жесткое сравнение необходимо, чтобы отличать введенный 0 и NULL из БД и т.д.
        }elseif(($int = (int)$form_value) !== self::$flatAssoc[$columnName]){
            
            self::$dataToUpdate[$columnName] = $int;
        }
    }
    
    // *** Поле в БД представлено строкой
    static public function addString(string $form_value, string $columnName):void {
        
        // В БД ничего не записано и из формы ничего не пришло : не обновляем данные
        if((is_null(self::$flatAssoc[$columnName]) || self::$flatAssoc[$columnName] === '') && $form_value === '') return;
    
        // Пользователь отправил данные, отличающиеся от записи в БД
        if(self::$flatAssoc[$columnName] !== $form_value) self::$dataToUpdate[$columnName] = $form_value;
    }
    
    
    // Предназначен для получения
    static public function isEmpty():bool {
        return empty(self::$dataToUpdate) ? true : false;
    }
    static public function get():array {
        return self::$dataToUpdate;
    }
}
