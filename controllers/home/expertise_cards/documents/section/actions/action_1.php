<?php


// -----------------------------------------------------------------------------------------
// Действие: "Создать описательную часть"
// -----------------------------------------------------------------------------------------


use Lib\Singles\VariableTransfer;
use Lib\Miscs\Initialization\Initializer as MiscsInitializer;
use Lib\Singles\DocumentTreeHandler;
use Lib\Singles\DocumentationFilesFacade;


$VT = VariableTransfer::getInstance();


$treeHandler = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree');

$applicationId = $treeHandler->getApplicationId();
$typeOfObjectId = $treeHandler->getTypeOfObjectId();


// Справочник "Критичность замечания"
//
$miscInitializer = new MiscsInitializer(['comment_criticality']);

$VT->setValue('comment_criticality', $miscInitializer->getPaginationSingleMiscs()['comment_criticality']);


// Структура документации с файлами
//
$documentationFilesFacade = new DocumentationFilesFacade($applicationId, $typeOfObjectId);

$mappings = $documentationFilesFacade->getMappingsLevel();

// Устанавливаем маппинги для работы js по скачиванию файлов
$VT->setValue('documentation_mapping_level_1', $mappings['1']);
$VT->setValue('documentation_mapping_level_2', $mappings['2']);

$nodeStructure = $documentationFilesFacade->getNodeStructure();

$filesInStructure = $documentationFilesFacade->getFilesInDepthStructure();

$uniqueIds = [];

// Формирование структуры документации, где есть загруженные файлы
// и всех их родительские узлы до самого старшего
foreach ($filesInStructure as $node) {

    if (isset($node['files'])) {

        $uniqueIds[] = $node['id'];

        foreach ($nodeStructure->getNodeParents($node['id'], $filesInStructure) as $parentId) {

            if (!in_array($parentId, $uniqueIds)) {

                $uniqueIds[] = $parentId;
            }
        }
    }
}

// Сортировка id разделов по возрастанию, чтобы стркутура отображалась корректно
sort($uniqueIds, SORT_NUMERIC);

$filesInStructure = array_filter($filesInStructure, fn($node) => (in_array($node['id'], $uniqueIds)));

DocumentationFilesFacade::handleFilesInStructure($filesInStructure);

$VT->setValue('documentation_files_in_structure', $filesInStructure);