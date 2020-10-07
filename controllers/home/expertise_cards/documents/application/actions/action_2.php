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
use Tables\Locators\TypeOfObjectTableLocator;


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
$tableLocator = new TypeOfObjectTableLocator($typeOfObjectId);


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


$nodeStructure = new NodeStructure(
    call_user_func([$tableLocator->getStructures(), 'getAllAssocWhereActive'])
);


// Переиндексация массива, т.к. дальше следует цикл for
$filesInStructure = array_values(FilesInitializer::getFilesInDepthStructure($needsFiles, $nodeStructure));

$offset = 0;


/**
 * Предназначен для получения информации - есть ли у узла дочерний узел
 * ведомостей объемов работ, у которого есть загруженные файлы
 *
 * @param int $currentIndex текущий индекс в перебираемом массиве
 * @return int|null <b>int</b> индекс элемента в исходном массиве, который
 * является дочерним узлом (к элементу currentIndex), ВОРом, в нем есть загруженные файлы<br>
 * <b>false</b> элемент не существует
 * @throws LogicException
 */
$hasVORWithFiles = function (int $currentIndex) use ($filesInStructure, &$offset): ?int {

    $indexes = [];

    // ВОРы должны быть обязательно после текущего элемента массива
    for ($l = ($offset + 1); $l < count($filesInStructure); $l++) {

        if (
            $filesInStructure[$l]['id_parent_node'] == $filesInStructure[$currentIndex]['id']
            && $filesInStructure[$l]['is_vor']
            && isset($filesInStructure[$l]['files'])
        ) {
            $indexes[] = $l;
        }
    }

    if (($count = count($indexes)) > 1) {
        $debug = implode(', ', $indexes);
        throw new LogicException("У узла с id: {$filesInStructure[$currentIndex]['id']} найдено: {$count} дочерних узлов ВОРов с id: '{$debug}'");
    }
    return $count == 1 ? $indexes[0] : null;
};


$filesInStructureTV = [];

// Отображаем только те разделы, к которым есть файлы и которые привязаны к 341 приказу

$ids = []; // Индексный массив id подошедших блоков из бланка заключения по 341 приказу

foreach ($filesInStructure as $index => $node) {

    // Раздел привязан к блоку из бланка заключения по 341 приказу
    if (!is_null($node['id_main_block_341'])) {

        $entryFlag = false;

        if (isset($node['files'])) {

            $tmpSelf = $filesInStructure[$index];

            FileHandler::setFileIconClass($tmpSelf['files']);
            FileHandler::setValidateResultJSON($tmpSelf['files']);
            FileHandler::setHumanFileSize($tmpSelf['files']);

            $tmpSelf['self_files'] = $tmpSelf['files'];
            unset($tmpSelf['files']);

            $filesInStructureTV[$index] = $tmpSelf;
            $entryFlag = true;
        }

        if (!is_null($vorIndex = $hasVORWithFiles($index))) {

            $VORFiles = $filesInStructure[$vorIndex]['files'];

            FileHandler::setFileIconClass($VORFiles);
            FileHandler::setValidateResultJSON($VORFiles);
            FileHandler::setHumanFileSize($VORFiles);

            // Если есть собственные файлы - записываем туда ключ vor_files.
            // Если файлов нет - нужно записать в массив TV этот раздел с ключом vor_files
            if ($entryFlag) {

                $filesInStructureTV[$index]['vor_files'] = $VORFiles;
            } else {

                // Если собственных файлов нет - надо добавить в массив для view исходный массив
                $tmpSelf = $filesInStructure[$index];
                $tmpSelf['vor_files'] = $VORFiles;
                unset($tmpSelf['files']);

                $filesInStructureTV[$index] = $tmpSelf;
                $entryFlag = true;
            }
        }
        if ($entryFlag) $ids[] = $filesInStructure[$index]['id_main_block_341'];
    }
    $offset++;
}

$variablesTV->setValue('documentation_files_in_structure', $filesInStructureTV);

// Формирование справочников разделов из 341 приказа
//
$miscInitializer = new MiscInitializer(
    call_user_func([$tableLocator->getOrder341MainBlock(), 'getAllAssocWhereIdNotInIds'], $ids)
);

foreach ($miscInitializer->getPaginationSingleMiscs() as $miscName => $misc) {
    $variablesTV->setValue($miscName, $misc);
}