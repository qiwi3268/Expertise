<?php

// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Крон предназначен для очистки системы от несохраненных файлов

require_once '/var/www/html/functions/functions.php';
require_once '/var/www/html/core/Classes/StartingInitialization.php';

$initializator = new StartingInitialization('/var/www/html');
$initializator->requireDefinedVariables();
$initializator->enableClassAutoloading();
$initializator->requireDataBasePack();

require_once _ROOT_.'/Classes/FilesTableMapping.php';
require_once _ROOT_.'/Classes/Logger.php';

$Logger = new Logger(_LOGS_.'/cron', 'fileCleaner.log');
$Logger->write("НАЧИНАЮ работу");

function FlushLogger(Logger $Logger){
    return function($buffer) use ($Logger){
        if(!empty($buffer)) $Logger->write('СООБЩЕНИЕ ИЗ БУФЕРА ВЫВОДА:'.PHP_EOL.$buffer);
    };
}
ob_start(FlushLogger($Logger));

try{
    DataBase::constructDB('ge');
}catch(DataBaseException $e){
    $Logger->write("ОШИБКА. Не удалось подключиться к БД. Текст ошибки: {$e->getMessage()}, код ошибки: {$e->getCode()}");
    exit();
}

foreach(_FILE_TABLE_MAPPING as $mapping_level_1_code => $mapping_level_2){
    
    foreach($mapping_level_2 as $mapping_level_2_code => $className){
        
        $Mapping = new FilesTableMapping($mapping_level_1_code, $mapping_level_2_code);
        
        if(!is_null($Mapping->getErrorCode())){
            $Logger->write("ОШИБКА в маппинг-таблице. Класс {$className}. {$Mapping->getErrorText()}");
            exit();
        }
        unset($Mapping);
        
        // Ассоциативный массив ненужных файлов
        $noNeedsAssoc = $className::getNoNeedsAssoc();
        
        if(is_null($noNeedsAssoc)){
            continue;
        }
        
        // Цикл по всем ненужным файлам таблицы класса className
        foreach($noNeedsAssoc as $file){
            
            $description = "Таблица класса: {$className}. Запись id: {$file['id']}. Название файла: {$file['file_name']}";
            
            // Ставим метку, если она еще не стоит
            if(!$file['cron_deleted_flag']){
                $className::setCronDeletedFlagById($file['id']);
                $Logger->write("Проставлена метка cron_deleted_flag. $description");
                continue;
            }
            
            // Метка проставлена, но дата null
            if(is_null($file['date_cron_deleted_flag'])){
                $Logger->write("ОТСУТСТВУЕТ ДАТА date_cron_deleted_flag. $description");
                continue;
            }
            
            // Метка уже была проставлена
            // Проверка, что с простановки последней метки прошло больше 23 часов
            $now = time();
            $minimumSeconds = 60 * 60 * 23;
            
            if($now - $file['date_cron_deleted_flag'] > $minimumSeconds){
                
                $applicationDir = _APPLICATIONS_FILES_."/{$file['id_application']}";
                $pathToFile = "{$applicationDir}/{$file['hash']}";
                
                if(!file_exists($pathToFile)){
                    $Logger->write("ОТСУТСТВУЕТ ФАЙЛ, который необходимо удалить по пути: {$pathToFile}. $description");
                    continue;
                }
                
                // Удаляем запись о файле
                try{
                    $className::deleteById($file['id']);
                }catch(DataBaseException $e){
                    $Logger->write("НЕ ПОЛУЧИЛОСЬ УДАЛИТЬ ЗАПИСЬ В ТАБЛИЦЕ. {$description}. Текст ошибки: {$e->getMessage()}, код ошибки: {$e->getCode()}");
                    continue;
                }
                
                // Удаляем файл
                if(!unlink($pathToFile)){
                    $Logger->write("НЕ ПОЛУЧИЛОСЬ УДАЛИТЬ ФАЙЛ. $description");
                    continue;
                }
                
                $Logger->write("Файл и его запись успешно удалены. $description");
            }
        }
    }
}

DataBase::closeDB();
$Logger->write("ЗАВЕРШАЮ работу");