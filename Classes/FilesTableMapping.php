<?php


// Класс проверки маппинга файловых таблиц
//
class FilesTableMapping{

    private string $neededInterface = 'Interface_fileTable';
    protected ?int $errorCode = null;
    protected string $errorText;
    protected string $Class;
    
    
    // Принимает параметры-----------------------------------
    // mappingLevel1 string : маппинг 1-го уровня константного массива _FILE_TABLE_MAPPING
    // mappingLevel2 string : маппинг 2-го уровня константного массива _FILE_TABLE_MAPPING
    //
    public function __construct(string $mappingLevel1, string $mappingLevel2){

        // Проверка существования маппинга
        if(!isset(_FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2])){
            $this->errorCode = 1;
            $this->errorText = "Запрашиваемого маппинга mapping_level_1: '{$mappingLevel1}', mapping_level_2: '{$mappingLevel2}'  не существует";
            return;
        }
    
        // Получение названия класса таблицы файлов
        $Class = _FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2];

        // Проверка на существование указанного в маппинге класса
        if(!class_exists($Class)){
            $this->errorCode = 2;
            $this->errorText = "Указанный в маппинге класс: '{$Class}' не существует";
            return;
        }

        $interfaces = class_implements($Class);

        // Проверка на реализацию интерфейса Interface_fileTable в нужном классе
        $neededInterface = $this->neededInterface;
        if(!$interfaces || !in_array($neededInterface, $interfaces, true)){
            $this->errorCode = 3;
            $this->errorText = "Указанный в маппинге класс: '{$Class}' не реализует требуемый интерфейс: '{$neededInterface}'";
            return;
        }

        $this->Class = $Class;
    }

    // Предназначен для получения кода ошибки при проверке маппинга
    // Возвращает параметры-----------------------------------
    // null  : нет ошибок
    // int   : есть ошибки
    //
    public function getErrorCode():?int {
        return $this->errorCode;
    }

    // Предназначен для получения текста ошибки при проверке маппинга
    // Возвращает параметры-----------------------------------
    // string : текст ошибки
    //
    public function getErrorText():string {
        return $this->errorText;
    }

    // Предназначен для получения названия класса из маппинга
    // Возвращает параметры-----------------------------------
    // string : имя класса
    //
    public function getClassName():string {
        return $this->Class;
    }
}