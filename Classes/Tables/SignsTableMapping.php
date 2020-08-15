<?php

class SignsTableMapping extends FilesTableMapping{
    
    private string $neededInterface = 'Interface_signTable';
    
    
    // *** Предполагается, что перед использованием данного класса маппинги были проверены классом FilesTableMapping,
    //     поэтому здесь предполагается, что они полностью корректны
    // Принимает параметры-----------------------------------
    // mappingLevel1 string : маппинг 1-го уровня константного массива _FILE_TABLE_MAPPING
    // mappingLevel2 string : маппинг 2-го уровня константного массива _FILE_TABLE_MAPPING
    //
    public function __construct(string $mappingLevel1, string $mappingLevel2){
    
        // Получение названия класса таблицы файлов
        $FileClass = _FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2];
        
        // Проверка существования класса таблицы подписей
        if(!isset(_SIGN_TABLE_MAPPING[$FileClass])){
            $this->errorCode = 1;
            $this->errorText = "Не существует соответствующего класса таблицы подписей к классу файловой таблицы: '{$FileClass}'";
            return;
        }
        
        // Получение названия класса таблицы подписей
        $SignClass = _SIGN_TABLE_MAPPING[$FileClass];
    
        $interfaces = class_implements($SignClass);
    
        // Проверка на реализацию интерфейса Interface_signTable в нужном классе
        if(!$interfaces || !in_array($this->neededInterface, $interfaces, true)){
            $this->errorCode = 3;
            $this->errorText = 'Указанный в маппинге класс не реализует требуемый интерфейс';
            return;
        }
    
        $this->Class = $SignClass;
        
        
        //define('_SIGN_TABLE_MAPPING', ['file_grbsTable' => 'sign_grbsTable']);
    }
}

