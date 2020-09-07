<?php

// -----------------------------------------------------------------------------------------
// Действие: "Назначить экспертов"
// -----------------------------------------------------------------------------------------


use Lib\Singles\VariableTransfer;
use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Singles\Helpers\FileHandler;
use Lib\Singles\NodeStructure;
use Tables\application;
use Classes\Application\Files\Initialization\Initializer as FilesInitializer;


$applicationId = $_GET['id_application'];

switch (application::getFlatAssocById($applicationId)['id_type_of_object']) {
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

$filesInitializer = new FilesInitializer($requiredMappings, $applicationId);

$needsFiles = $filesInitializer->getNeedsFilesWithSigns()[$mapping_level_1][$mapping_level_2];

// Обработка файловых массивов
FileHandler::setFileIconClass($needsFiles);
FileHandler::setValidateResultJSON($needsFiles);
FileHandler::setHumanFileSize($needsFiles);

$nodeStructure = new NodeStructure($className::getAllActive());

$filesInStructure = FilesInitializer::getFilesInDepthStructure($needsFiles, $nodeStructure);

$structureWithFiles = array_filter($filesInStructure, fn($node) => isset($node['files']));

VariableTransfer::getInstance()->setValue('documentation_files_in_structure', $structureWithFiles);