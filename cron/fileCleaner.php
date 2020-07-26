<?php


// Крон предназначен для очистки системы от несохраненных файлов

require_once '/var/www/html/core/Classes/StartingInitialization.php';

$initializator = new StartingInitialization('/var/www/html');
$initializator->requireDefinedVariables();
$initializator->enableClassAutoloading();
$initializator->requireDataBasePack();

require_once  _ROOT_.'/Classes/Logger.php';

try{
    DataBase::constructDB('ge');
}catch(DataBaseException $e){
    $Logger->write("ОШИБКА. Не удалось подключиться к БД. Текст ошибки: {$e->getMessage()}, код ошибки: {$e->getCode()}");
    exit();
}


$Logger = new Logger(_ROOT_.'/logs/cron', 'fileCleaner.log');
$Logger->write("НАЧИНАЮ работу");

foreach(_FILE_TABLE_MAPPING as $mapping_level_1){
    
    foreach($mapping_level_1 as $className){
    
        // Проверка
        if(!class_exists($className)){
            $Logger->write("ОШИБКА. Класс {$className}, указанный в _FILE_TABLE_MAPPING не существует");
            exit();
        }
    
        $interfaces = class_implements($className);
    
        if(!$interfaces || !in_array('Interface_fileTable', $interfaces, true)){
            $Logger->write("ОШИБКА. Класс {$className}, указанный в _FILE_TABLE_MAPPING не реализует требуемый интерфейс Interface_fileTable");
            exit();
        }
        
        // Ассоциативный массив ненужных файлов
        $noNeedsAssoc = $className::getNoNeedsAssoc();
        
        if(is_null($noNeedsAssoc)){
            continue;
        }
        
        foreach($noNeedsAssoc as $file){

            // Ставим метку, если она еще не стоит
            if(!$file['cron_deleted_flag']){
                $className::setCronDeletedFlagById($file['id']);
                $Logger->write("Запись id: {$file['id']} таблицы класса {$className} проставлена метка cron_deleted_flag");
                continue;
            }
            
            // Метка уже была проставлена
            // Проверка, что с простановки последней метки прошло больше 23 часов
            $now = time();
            $minimumSeconds = 60 * 60 * 23;
            $minimumSeconds = 60 * 3;
            
            if($now - $file['date_cron_deleted_flag'] > $minimumSeconds){
    
                $applicationDir = _APPLICATIONS_FILES_."/{$file['id_application']}";
                $pathToFile = "{$applicationDir}/{$file['hash']}";
                
                if(!file_exists($pathToFile)){
                    $Logger->write("ОТСУТСТВУЕТ ФАЙЛ. id: {$file['id']} таблицы класса {$className}, который необходимо удалить: {$pathToFile}");
                    continue;
                }
                
                // Удаляем файл
                if(!unlink($pathToFile)){
                    $Logger->write("НЕ ПОЛУЧИЛОСЬ УДАЛИТЬ ФАЙЛ. id: {$file['id']} таблицы класса {$className}");
                    continue;
                }
                
                // Удаляем запись о файле
                try{
                    $className::deleteById($file['id']);
                }catch(DataBaseException $e){
                    $Logger->write("НЕ ПОЛУЧИЛОСЬ УДАЛИТЬ ЗАПИСЬ В ТАБЛИЦЕ. id: {$file['id']} таблицы класса {$className}. Текст ошибки: {$e->getMessage()}, код ошибки: {$e->getCode()}");
                    continue;
                }
    
                $Logger->write("Успех. Файл и его запись id: {$file['id']} таблицы класса {$className} успешно удалены");
            }
        }
    }
}

DataBase::closeDB();
$Logger->write("ЗАВЕРШАЮ работу");