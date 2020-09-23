<?php


use Lib\Responsible\Responsible;
use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Singles\VariableTransfer;
use Lib\Singles\NodeStructure;
use Lib\Singles\Helpers\FileHandler;
use Classes\Application\Files\Initialization\Initializer as FilesInitializer;
use Tables\Docs\application;


$variablesTV = VariableTransfer::getInstance();

$applicationAssoc = application::getAssocById(CURRENT_DOCUMENT_ID);

// Преобразование дат к строкам
updateDatesTimestampToDdMmYyyy(
    $applicationAssoc,
    'date_planning_documentation_approval',
    'date_GPZU',
    'date_finish_building'
);

// Заполнение сохраненных в заявлении данных (не включая файлы)
foreach ($applicationAssoc as $property => $value) {

    if (is_null($value)) {
        $variablesTV->setExistenceFlag($property, false);
        continue;
    }

    $variablesTV->setExistenceFlag($property, true);
    $variablesTV->setValue($property, $value);
}


// Сохраненные файлы анкеты (не включая документацию)
$requiredMappings = new RequiredMappingsSetter();
$requiredMappings->setMappingLevel1(1);

$filesInitializer = new FilesInitializer($requiredMappings, CURRENT_DOCUMENT_ID);

$needsFiles = $filesInitializer->getNeedsFilesWithSigns();


// Обработка файловых массивов
foreach ($needsFiles as &$mapping_level_2) {
    foreach ($mapping_level_2 as &$files) {
        if (!is_null($files)) {
            FileHandler::setFileIconClass($files);
            FileHandler::setValidateResultJSON($files);
            FileHandler::setHumanFileSize($files);
        }
    }
    unset($files);
}
unset($mapping_level_2);

$variablesTV->setValue('form_files', $needsFiles);

// Сохранен вид объекта, показываем документацию
if ($variablesTV->getExistenceFlag('type_of_object')) {

    // Удаление переменных, служивших выше
    unset($requiredMappings, $filesInitializer, $needsFiles);

    // В зависимости от вида объекта выбираем нужную таблицу
    switch ($variablesTV->getValue('type_of_object')['id']) {
        case 1 : // Производственные / непроизводственные
            $mapping_level_1 = 2;
            $mapping_level_2 = 1;
            $className = '\Tables\Structures\documentation_1';
            break;
        case 2 : // Линейные
            $mapping_level_1 = 2;
            $mapping_level_2 = 2;
            $className = '\Tables\Structures\documentation_2';
            break;
        default :
            throw new Exception('Указан вид объекта, при котором не определены действия для отображения загруженных файлов');
    }

    // Устанавливаем маппинги для работы js по скачиванию файлов
    $variablesTV->setValue('documentation_mapping_level_1', $mapping_level_1);
    $variablesTV->setValue('documentation_mapping_level_2', $mapping_level_2);

    // Объект нужных маппингов (только документация выбранного вида объекта)
    $requiredMappings = new RequiredMappingsSetter();

    $requiredMappings->setMappingLevel2($mapping_level_1, $mapping_level_2);

    $filesInitializer = new FilesInitializer($requiredMappings, CURRENT_DOCUMENT_ID);

    $needsFiles = $filesInitializer->getNeedsFilesWithSigns()[$mapping_level_1][$mapping_level_2] ?? [];

    // Обработка файловых массивов
    FileHandler::setFileIconClass($needsFiles);
    FileHandler::setValidateResultJSON($needsFiles);
    FileHandler::setHumanFileSize($needsFiles);

    $nodeStructure = new NodeStructure($className::getAllAssocWhereActive());

    $filesInStructure = FilesInitializer::getFilesInDepthStructure($needsFiles, $nodeStructure);

    $variablesTV->setValue('documentation_files_in_structure', $filesInStructure);
}

//todo перенести вместе с use в другое место

$responsible = new Responsible(CURRENT_DOCUMENT_ID, CURRENT_DOCUMENT_TYPE);
$responsible = $responsible->getCurrentResponsible();


//var_dump($responsible);







