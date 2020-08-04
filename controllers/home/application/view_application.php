<?php


$variablesTV = VariableTransfer::getInstance();
$applicationId = $_GET['id_application'];

$applicationAssoc = ApplicationsTable::getAssocById($applicationId);

// Преобразование дат к строкам
UpdateDatesTimestampToDdMmYyyy($applicationAssoc,
                        'date_planning_documentation_approval',
                                           'date_GPZU',
                                           'date_finish_building');

// Заполнение сохраненных в заявлении данных (не включая файлы)
foreach($applicationAssoc as $property => $value){
    
    if(is_null($value)){
        $variablesTV->setExistenceFlag($property, false);
        continue;
    }
    
    $variablesTV->setExistenceFlag($property, true);
    $variablesTV->setValue($property, $value);
}


// Сохраненные файлы анкеты (не включая документацию)
$requiredMappings = new RequiredMappingsSetter();
$requiredMappings->setMappingLevel1(1);

$filesInitialization = new FilesInitialization($requiredMappings, $applicationId);
$needsFiles = $filesInitialization->getNeedsFiles();

// Установка файловых иконок
foreach($needsFiles as &$mapping_level_2){
    foreach($mapping_level_2 as &$files){
        if(!is_null($files)){
            FontAwesome5Helper::setFileIconClass($files);
        }
    }
    unset($files);
}
unset($mapping_level_2);

$variablesTV->setValue('form_files', $needsFiles);

var_dump($variablesTV->getValue('form_files')[1][1]);

// Сохранен Вид объекта, показываем документацию
if($variablesTV->getExistenceFlag('type_of_object')){
    
    // Удаление переменных, служивших выше
    unset($requiredMappings);
    unset($filesInitialization);
    unset($needsFiles);
    
    // В зависимости от Вида объекта выбираем нужную таблицу
    switch($variablesTV->getValue('type_of_object')['id']){
        case 1: // Производственные/непроизводственные
            $mapping_level_1 = 2;
            $mapping_level_2 = 1;
            $className = 'structure_documentation1Table';
            break;
        case 2: // Линейные
            $mapping_level_1 = 2;
            $mapping_level_2 = 2;
            $className = 'structure_documentation2Table';
            break;
        default:
            throw new Exception('Указан Вид объекта, при котором не определены действия для отображения загруженных файлов');
    }
    
    // Устанавливаем маппинги для работы js по скачиванию файлов
    $variablesTV->setValue('documentation_mapping_level_1', $mapping_level_1);
    $variablesTV->setValue('documentation_mapping_level_2', $mapping_level_2);
    
    // Структура разделов документации
    $structureDocumentation = $className::getAllActive();
    $NodeStructure = new NodeStructure($structureDocumentation);
    
    // Объект нужных маппингов (только документация выбранного Вида объекта)
    $requiredMappings = new RequiredMappingsSetter();
    $requiredMappings->setMappingLevel2($mapping_level_1, $mapping_level_2);
    
    $filesInitialization = new FilesInitialization($requiredMappings, $applicationId);
    // Нужные (is_needs) файлы
    $needsFiles = $filesInitialization->getNeedsFiles()[$mapping_level_1][$mapping_level_2];
    
    // В документации нет загруженных файлов
    if(is_null($needsFiles)){
        $needsFiles = [];
    }else{
        // Установка файловых иконок
        FontAwesome5Helper::setFileIconClass($needsFiles);
    }
    
    // Структура разделов документации с вложенностью дочерних разделов в родительские
    $depthStructure = $NodeStructure->getDepthStructure();
    $filesInStructure = $filesInitialization->getFilesInStructure($needsFiles, $depthStructure);
    //var_dump($filesInStructure);
    $variablesTV->setValue('documentation_files_in_structure', $filesInStructure);
}

$test = shell_exec('lala 2>&1');
//var_dump($test);



