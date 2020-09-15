<?php

// -----------------------------------------------------------------------------------------
// Действие: "Назначить экспертов"
// -----------------------------------------------------------------------------------------


use Lib\Singles\VariableTransfer;
use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Singles\Helpers\FileHandler;
use Lib\Singles\NodeStructure;
use Tables\application;
use Tables\user;
use Classes\Application\Files\Initialization\Initializer as FilesInitializer;


$variablesTV = VariableTransfer::getInstance();


$activeExperts = user::getActiveExperts();

foreach ($activeExperts as &$expert) {
    $expert['fio'] = getFIO($expert);
    unset($expert['last_name'], $expert['first_name'],$expert['middle_name']);
}
unset($expert);

$variablesTV->setValue('experts', $activeExperts);


switch (application::getFlatAssocById(CURRENT_DOCUMENT_ID)['id_type_of_object']) {
    case 1: // Производственные/непроизводственные
        $mapping_level_1 = 2;
        $mapping_level_2 = 1;
        $className = '\Tables\Structures\documentation_1';
        break;
    case 2: // Линейные
        $mapping_level_1 = 2;
        $mapping_level_2 = 2;
        $className = '\Tables\Structures\documentation_2';
        break;
    default:
        throw new Exception('Указан Вид объекта, при котором не определены действия для отображения загруженных файлов');
}

// Объект нужных маппингов (только документация выбранного Вида объекта)
$requiredMappings = new RequiredMappingsSetter();

$requiredMappings->setMappingLevel2($mapping_level_1, $mapping_level_2);

$filesInitializer = new FilesInitializer($requiredMappings, CURRENT_DOCUMENT_ID);

$needsFiles = $filesInitializer->getNeedsFilesWithSigns()[$mapping_level_1][$mapping_level_2];

// Обработка файловых массивов
FileHandler::setFileIconClass($needsFiles);
FileHandler::setValidateResultJSON($needsFiles);
FileHandler::setHumanFileSize($needsFiles);



$nodeStructure = new NodeStructure($className::getAllActive());

$filesInStructure = FilesInitializer::getFilesInDepthStructure($needsFiles, $nodeStructure);

// Отображаем только те разделы, к которым есть файлы и которые привязаны к 341 приказу

$ids = []; // Индексный массив id подошедших блоков  в бланке заключения по 341 приказу

foreach ($filesInStructure as $index => $node) {

    if (isset($node['files']) && !is_null($node['id_341_main_block'])) {

        $ids[] = (int)$node['id_341_main_block'];

    } else {

        unset($filesInStructure[$index]);
    }
}


var_dump($ids);
//var_dump($filesInStructure);

VariableTransfer::getInstance()->setValue('documentation_files_in_structure', $filesInStructure);