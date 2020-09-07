<?php


use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Singles\VariableTransfer;
use Lib\Singles\NodeStructure;
use Lib\Singles\Helpers\FileHandler;
use Classes\Application\Files\Initialization\Initializator as FilesInitializator;
use Classes\Application\Responsible as ApplicationResponsible;
use Tables\application;


$variablesTV = VariableTransfer::getInstance();
$applicationId = $_GET['id_application'];

$applicationAssoc = application::getAssocById($applicationId);

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

$FilesInitializator = new FilesInitializator($requiredMappings, $applicationId);

$needsFiles = $FilesInitializator->getNeedsFilesWithSigns();


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

var_dump($variablesTV->getValue('form_files')[1][1]);

// Сохранен Вид объекта, показываем документацию
if ($variablesTV->getExistenceFlag('type_of_object')) {

    // Удаление переменных, служивших выше
    unset($requiredMappings, $FilesInitializator, $needsFiles);

    // В зависимости от Вида объекта выбираем нужную таблицу
    switch ($variablesTV->getValue('type_of_object')['id']) {
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

    // Устанавливаем маппинги для работы js по скачиванию файлов
    $variablesTV->setValue('documentation_mapping_level_1', $mapping_level_1);
    $variablesTV->setValue('documentation_mapping_level_2', $mapping_level_2);

    // Объект нужных маппингов (только документация выбранного Вида объекта)
    $requiredMappings = new RequiredMappingsSetter();

    $requiredMappings->setMappingLevel2($mapping_level_1, $mapping_level_2);

    $FilesInitializator = new FilesInitializator($requiredMappings, $applicationId);

    $needsFiles = $FilesInitializator->getNeedsFilesWithSigns()[$mapping_level_1][$mapping_level_2] ?? [];

    // Обработка файловых массивов
    FileHandler::setFileIconClass($needsFiles);
    FileHandler::setValidateResultJSON($needsFiles);
    FileHandler::setHumanFileSize($needsFiles);

    $NodeStructure = new NodeStructure($className::getAllActive());

    $filesInStructure = FilesInitializator::getFilesInDepthStructure($needsFiles, $NodeStructure);

    var_dump($filesInStructure);

    $variablesTV->setValue('documentation_files_in_structure', $filesInStructure);
}

//todo перенести вместе с use в другое место

$ApplicationResponsible = new ApplicationResponsible($applicationId);
$responsible = $ApplicationResponsible->getResponsible();


var_dump($responsible);







