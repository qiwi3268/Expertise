<?php


use Lib\Singles\VariableTransfer;
use Classes\Application\Files\Initialization\DocumentationFilesFacade;
use Classes\Application\Files\Initialization\FormFilesInitializer;
use Tables\Docs\application;


$VT = VariableTransfer::getInstance();

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
        $VT->setExistenceFlag($property, false);
        continue;
    }

    $VT->setExistenceFlag($property, true);
    $VT->setValue($property, $value);
}


// Сохраненные файлы анкеты (не включая документацию)
$formFilesInitializer = new FormFilesInitializer(CURRENT_DOCUMENT_ID);

$needsFiles = $formFilesInitializer->getNeedsFilesWithSigns();


// Обработка файловых массивов
foreach ($needsFiles as &$mapping_level_2) {
    foreach ($mapping_level_2 as &$files) {
        if (!is_null($files)) {
            DocumentationFilesFacade::handleFiles($files);
        }
    }
    unset($files);
}
unset($mapping_level_2);

$VT->setValue('form_files', $needsFiles);

// Сохранен вид объекта, показываем документацию
if ($VT->getExistenceFlag('type_of_object')) {

    $documentationFilesFacade = new DocumentationFilesFacade(CURRENT_DOCUMENT_ID, $VT->getValue('type_of_object')['id']);

    $mappings = $documentationFilesFacade->getMappingsLevel();

    // Устанавливаем маппинги для работы js по скачиванию файлов
    $VT->setValue('documentation_mapping_level_1', $mappings['1']);
    $VT->setValue('documentation_mapping_level_2', $mappings['2']);

    $filesInStructure = $documentationFilesFacade->getFilesInDepthStructure();
    DocumentationFilesFacade::handleFilesInStructure($filesInStructure);

    $VT->setValue('documentation_files_in_structure', $filesInStructure);
}