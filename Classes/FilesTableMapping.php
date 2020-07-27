<?php


// Класс проверки маппинга файловых таблиц
//
class FilesTableMapping{

    private string $neededInterface = 'Interface_fileTable';
    private ?int $errorCode = null;
    private string $errorText;
    private string $Class;

    public function __construct(string $mappingLevel1, string $mappingLevel2){

        // Проверка существования маппинга
        if(!isset(_FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2])){
            $this->errorCode = 1;
            $this->errorText = 'Запрашиваемого маппинга не существует';
            return;
        }

        $Class = _FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2];

        // Проверка на существование указанного в маппинге класса
        if(!class_exists($Class)){
            $this->errorCode = 2;
            $this->errorText = 'Указанного в маппинге класса не существует';
            return;
        }

        $interfaces = class_implements($Class);

        // Проверка на реализацию интерфейса Interface_fileTable в нужном классе
        if(!$interfaces || !in_array($this->neededInterface, $interfaces, true)){
            $this->errorCode = 3;
            $this->errorText = 'Указанный в маппинге класс не реализует требуемый интерфейс';
            return;
        }

        $this->Class = $Class;
    }

    // Предназначен для получения кода ошибки при проверке маппинга
    // Возвращает параметры-----------------------------------
    // null  : нет ошибок
    // int   : есть ошибки
    //
    public function getErrorCode(){
        return $this->errorCode;
    }

    // Предназначен для получения текста ошибки при проверке маппинга
    // Возвращает параметры-----------------------------------
    // string : текст ошибки
    //
    public function getErrorText(){
        return $this->errorText;
    }

    // Предназначен для получения названия класса из маппинга
    // Возвращает параметры-----------------------------------
    // string : имя класса
    //
    public function getClassName(){
        return $this->Class;
    }
}