<?php

// Крон предназначен для очистки системы от несохраненных файлов

$variablesTV = \Lib\Singles\VariableTransfer::getInstance();

$logger = $variablesTV->getValue('Logger');
$errorLogger =  $variablesTV->getValue('ErrorLogger');

$logger->write("НАЧИНАЮ работу");

require_once ROOT.'/Lib/Files/Mappings/FilesTableMapping.php';

foreach(FILE_TABLE_MAPPING as $mapping_level_1_code => $mapping_level_2){
    
    foreach($mapping_level_2 as $mapping_level_2_code => $className){
        
        $Mapping = new \Lib\Files\Mappings\FilesTableMapping($mapping_level_1_code, $mapping_level_2_code);
        
        if(!is_null($Mapping->getErrorCode())){
            $errorLogger->write("ОШИБКА в маппинг-таблице. Класс {$className}. {$Mapping->getErrorText()}");
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
                $logger->write("Проставлена метка cron_deleted_flag. $description");
                continue;
            }
            
            // Метка проставлена, но дата null
            if(is_null($file['date_cron_deleted_flag'])){
                $errorLogger->write("ОТСУТСТВУЕТ ДАТА date_cron_deleted_flag. $description");
                continue;
            }
            
            // Метка уже была проставлена
            // Проверка, что с простановки последней метки прошло больше 23 часов
            $now = time();
            $minimumSeconds = 60 * 60 * 23;
            
            if($now - $file['date_cron_deleted_flag'] > $minimumSeconds){
                
                $applicationDir = APPLICATIONS_FILES."/{$file['id_application']}";
                $pathToFile = "{$applicationDir}/{$file['hash']}";
                
                if(!file_exists($pathToFile)){
                    $errorLogger->write("ОТСУТСТВУЕТ ФАЙЛ, который необходимо удалить по пути: {$pathToFile}. $description");
                    continue;
                }
                
                // Удаляем запись о файле
                try{
                    $className::deleteById($file['id']);
                }catch(DataBaseException $e){
                    $errorLogger->write("НЕ ПОЛУЧИЛОСЬ УДАЛИТЬ ЗАПИСЬ В ТАБЛИЦЕ. {$description}. Текст ошибки: {$e->getMessage()}, код ошибки: {$e->getCode()}");
                    continue;
                }
                
                // Удаляем файл
                if(!unlink($pathToFile)){
                    $errorLogger->write("НЕ ПОЛУЧИЛОСЬ УДАЛИТЬ ФАЙЛ. $description");
                    continue;
                }
                
                $logger->write("Файл и его запись успешно удалены. $description");
            }
        }
    }
}
$logger->write("ЗАВЕРШАЮ работу");