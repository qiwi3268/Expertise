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

// Получение данных о выбранном Виде объекта для выбора нужных классов
//
switch (application::getFlatAssocById(CURRENT_DOCUMENT_ID)['id_type_of_object']) {
    case 1 : // Производственные/непроизводственные
        $mapping_level_1 = 2;
        $mapping_level_2 = 1;
        $structureDocumentationClassName = '\Tables\Structures\documentation_1';
        $mainBlocks341DocumentationClassName = '\Tables\order_341\main_block_documentation_1';
        break;
    case 2 : // Линейные
        $mapping_level_1 = 2;
        $mapping_level_2 = 2;
        $structureDocumentationClassName = '\Tables\Structures\documentation_2';
        //todo $mainBlocks341DocumentationClassName = '\Tables\order_341\main_block_documentation_1';
        break;
    default :
        throw new Exception('Указан Вид объекта, при котором не определены действия для отображения загруженных файлов');
}

// Формирование разделов и загруженной к ним документации
//
// Объект нужных маппингов (только документация выбранного Вида объекта)
$requiredMappings = new RequiredMappingsSetter();

$requiredMappings->setMappingLevel2($mapping_level_1, $mapping_level_2);

$filesInitializer = new FilesInitializer($requiredMappings, CURRENT_DOCUMENT_ID);

$needsFiles = $filesInitializer->getNeedsFilesWithSigns()[$mapping_level_1][$mapping_level_2];

// Обработка файловых массивов
FileHandler::setFileIconClass($needsFiles);
FileHandler::setValidateResultJSON($needsFiles);
FileHandler::setHumanFileSize($needsFiles);


$nodeStructure = new NodeStructure($structureDocumentationClassName::getAllActive());

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

VariableTransfer::getInstance()->setValue('documentation_files_in_structure', $filesInStructure);

// Формирование справочников разделов из 341 приказа
//
$mainBlocks341 = $mainBlocks341DocumentationClassName::getAllAssocWhereIdNotInIds($ids);

$miscInitializer = new MiscInitializer($mainBlocks341);

foreach ($miscInitializer->getPaginationSingleMiscs() as $miscName => $misc) {
    $variablesTV->setValue($miscName, $misc);
}

$test = [
    [
        'id_expert'   => 1,
        'lead'        => true,
        'common_part' => false,
        'ids_main_block_341' => [1, 2, 3, 4]
    ],

    [
        'id_expert'   => 2,
        'lead'        => false,
        'common_part' => false,
        'ids_main_block_341' => [5]
    ],

    [
        'id_expert'   => 6,
        'lead'        => false,
        'common_part' => true,
        'ids_main_block_341' => [3, 4]
    ],
];

var_dump($test);
var_dump(json_encode($test));