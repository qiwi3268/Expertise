<?php


// Предназначен для логирования действий
//
class Logger{
    
    private string $logsDir;
    private string $logsName;
    
    
    // Принимает параметры-----------------------------------
    // logsDir  string : абсолютный путь на сервере до директории логов. Без символа '/' в конце строки
    // logsName string : название файла логов от директории логов, включая расширение файла
    //
    function __construct(string $logsDir, string $logsName){

        $this->changeLogsDir($logsDir);
        $this->changeLogsName($logsName);
        
        if(!file_exists("{$logsDir}/{$logsName}")){
            
            if(!file_exists($this->logsDir)){
                throw new Exception("Указанная директория лог файлов: '{$this->logsDir}' не существует в файловой системе сервера");
            }else{
                throw new Exception("Указанный файл логов: '{$this->logsName}' не существует в директории {$this->logsDir}");
            }
        }
    }
    
    
    // Предназначен для смены свойства класса с учетом проверки
    // Принимает параметры-----------------------------------
    // logsDir string: абсолютный путь к директории с файлами логов
    //
    public function changeLogsDir(string $logsDir):void {
        if(!$this->validateLogsDir($logsDir)) throw new Exception("Передан некорректный параметр logsDir: '{$logsDir}'");
        $this->logsDir = $logsDir;
    }
    
    
    // Предназначен для смены свойства класса с учетом проверки
    // Принимает параметры-----------------------------------
    // logsName string: имя файла логов
    //
    public function changeLogsName(string $logsName):void {
        if(!$this->validateLogsName($logsName)) throw new Exception("Передан некорректный параметр logsName: '{$logsName}'");
        $this->logsName = $logsName;
    }
    
    
    // Предназначен для записи логов
    // Принимает параметры-----------------------------------
    // message string: логируемое сообщение
    // Возвращает параметры-----------------------------------
    // string : временная отметка логируемого сообщения
    //
    public function write(string $message):string {
        
        $date = date('d.m.Y H:i:s');
        $message = "{$date} {$message}".PHP_EOL;
        if(file_put_contents("{$this->logsDir}/{$this->logsName}", $message, FILE_APPEND) === false){
            throw new Exception("Произошла ошибка при попытке записать логируемое сообщение: '{$message}' в файл: '{$this->logsDir}/{$this->logsName}'");
        }
        return $date;
    }
    
    
    // Предназначен для валидации директории файла логов
    // Путь к директории должен начинться с '/' и не должен заканчиваться на '/'
    // Принимает параметры-----------------------------------
    // logsDir string: абсолютный путь к директории с файлами логов
    // Возвращает параметры-----------------------------------
    // true  : проверка пройдена
    // false : проверка не пройдена
    //
    private function validateLogsDir(string $logsDir):bool {
        if($logsDir[0] !== '/' || $logsDir[mb_strlen($logsDir) - 1] === '/'){
            return false;
        }
        return true;
    }
    
    
    // Предназначен для валидации имени файла логов
    // Название файла не должно начинаться с '/'
    // Принимает параметры-----------------------------------
    // logsName string: имя файла логов
    // Возвращает параметры-----------------------------------
    // true  : проверка пройдена
    // false : проверка не пройдена
    //
    private function validateLogsName(string $logsName):bool {
        
        return $logsName[0] == '/' ? false : true;
    }
}
