<?php


interface Interface_fileTable{
    
    // Предназначен для удаления записи из файловой таблицы
    //
    static public function deleteById(int $id):void;
    
    // Предназначен для установки флага загрузки файла на сервер
    //
    static public function setUploadedById(int $id):void;
    
    // Предназначен для получения ассоциативного массива записи по id
    //
    static public function getAssocById(int $id):?array;
    
    // Предназнчен для получения ассоциативного массива ненужных файлов
    //
    static public function getNoNeedsAssoc():?array;
    
    // Предназначен для установки флага удаления крона по id
    //
    static public function setCronDeletedFlagById(int $id):void;
}