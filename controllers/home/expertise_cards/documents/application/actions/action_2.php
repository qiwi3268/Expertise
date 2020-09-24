<?php


// -----------------------------------------------------------------------------------------
// Действие: "Назначить экспертов"
// -----------------------------------------------------------------------------------------


use Lib\Singles\VariableTransfer;
use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Singles\Helpers\FileHandler;
use Lib\Singles\NodeStructure;
use Classes\Application\Files\Initialization\Initializer as FilesInitializer;
use Classes\Application\Actions\Miscs\Initialization\action_2 as MiscInitializer;
use Tables\Docs\application;
use Tables\user;
use Tables\DocumentationTypeTableLocator;


$variablesTV = VariableTransfer::getInstance();

// Формирование экспертов для назначения на разделы
//
$activeExperts = user::getActiveExperts();

foreach ($activeExperts as &$expert) {
    $expert['fio'] = getFIO($expert);
    unset($expert['last_name'], $expert['first_name'],$expert['middle_name']);
}
unset($expert);

$variablesTV->setValue('experts', $activeExperts);

// Получение данных о выбранном виде объекта для выбора нужных классов
//
$typeOfObjectId = application::getIdTypeOfObjectById(CURRENT_DOCUMENT_ID);
$tableLocator = new DocumentationTypeTableLocator($typeOfObjectId);


if ($typeOfObjectId == 1) { // Производственные / непроизводственные
    $mapping_level_1 = 2;
    $mapping_level_2 = 1;
} else {                    // Линейные
    $mapping_level_1 = 2;
    $mapping_level_2 = 2;
}


// Формирование разделов и загруженной к ним документации
//
// Объект нужных маппингов (только документация выбранного вида объекта)
$requiredMappings = new RequiredMappingsSetter();

$requiredMappings->setMappingLevel2($mapping_level_1, $mapping_level_2);

$filesInitializer = new FilesInitializer($requiredMappings, CURRENT_DOCUMENT_ID);

$needsFiles = $filesInitializer->getNeedsFilesWithSigns()[$mapping_level_1][$mapping_level_2];

// Обработка файловых массивов
FileHandler::setFileIconClass($needsFiles);
FileHandler::setValidateResultJSON($needsFiles);
FileHandler::setHumanFileSize($needsFiles);


$nodeStructure = new NodeStructure(
    call_user_func([$tableLocator->getStructures(), 'getAllAssocWhereActive'])
);

$filesInStructure = FilesInitializer::getFilesInDepthStructure($needsFiles, $nodeStructure);

// Отображаем только те разделы, к которым есть файлы и которые привязаны к 341 приказу

$ids = []; // Индексный массив id подошедших блоков из бланка заключения по 341 приказу

foreach ($filesInStructure as $index => $node) {

    if (isset($node['files']) && !is_null($node['id_main_block_341'])) {

        $ids[] = (int)$node['id_main_block_341'];
    } else {

        unset($filesInStructure[$index]);
    }
}

$variablesTV->setValue('documentation_files_in_structure', $filesInStructure);

// Формирование справочников разделов из 341 приказа
//
$miscInitializer = new MiscInitializer(
    call_user_func([$tableLocator->getOrder341MainBlock(), 'getAllAssocWhereIdNotInIds'], $ids)
);

foreach ($miscInitializer->getPaginationSingleMiscs() as $miscName => $misc) {
    $variablesTV->setValue($miscName, $misc);
}