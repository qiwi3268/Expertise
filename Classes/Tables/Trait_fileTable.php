<?php


// Трейт, реализующий интерфейс Interface_fileTable
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait  Trait_fileTable{
    
    
    // Реализация метода интерфейса
    // Предназначен для удаления записи из файловой таблицы
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function deleteById(int $id):void {
        
        $table = self::$tableName;
        
        $query = "DELETE
                  FROM `$table`
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }
    
    
    // Реализация метода интерфейса
    // Предназначен для установки флага загрузки файла на сервер
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function setUploadedById(int $id):void {
        
        $table = self::$tableName;
        
        $query = "UPDATE `$table`
                  SET `is_uploaded`='1'
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }
    
    
    // Реализация метода интерфейса
    // Предназначен для получения ассоциативного массива записи по id
    // Принимает параметры-----------------------------------
    // id int : id записи
    // Возвращает параметры-----------------------------------
    // array : в случае, если запись существует
    // null  : в противном случае
    //
    static public function getAssocById(int $id):?array {
        
        $table = self::$tableName;
        
        $query = "SELECT *
                  FROM `$table`
                  where `id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }
    
    
    // Реализация метода интерфейса
    // Предназнчен для получения ассоциативного массива ненужных файлов
    // Возвращает параметры-----------------------------------
    // array : в случае, если записи существуют
    // null  : в противном случае
    //
    static public function getNoNeedsAssoc():?array {
    
        $table = self::$tableName;
        
        $query = "SELECT *
                  FROM `$table`
                  WHERE `is_needs`=0";
        
        $result = SimpleQuery::getFetchAssoc($query);
    
        return $result ? $result : null;
    }
    
    
    // Реализация метода интерфейса
    // Предназначен для установки флага удаления крона по id
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function setCronDeletedFlagById(int $id):void {
    
        $table = self::$tableName;
    
        $query = "UPDATE `$table`
                  SET `cron_deleted_flag`='1', `date_cron_deleted_flag`=UNIX_TIMESTAMP()
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }
    
}