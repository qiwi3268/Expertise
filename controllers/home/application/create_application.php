<?php


$variablesTV = VariableTransfer::getInstance();

$userInfo = Session::getUserInfo();
$userId = $userInfo['id'];

// Инкрементируем и получаем внутренний счетчик заявления
ApplicationCounterTable::incrementInternal();
$internalCounter = ApplicationCounterTable::getInternal();

// Числовое имя
$appNumName = ApplicationHelper::getInternalAppNumName($internalCounter);

$applicationId = ApplicationsTable::createTemporary($userId, $appNumName);

var_dump($applicationId);

// Создание директории заявления
if(!mkdir(_APPLICATIONS_FILES_."/$applicationId")){
    exit('Не удалось создать директорию заявления');
}

// Установка прав на папку. Устанавливается отдельно, т.к. на уровне ОС стоит umask
if(!chmod(_APPLICATIONS_FILES_."/$applicationId", 0757)){
    exit('Не удалось задать права на директорию');
}

// Добавляем созданное заявление в сессию
Session::addAuthorRoleApplicationId($applicationId);

$variablesTV->setValue('numerical_name', $appNumName);
$variablesTV->setValue('id_application', $applicationId);

// Справочники
$miscInitialization = new MiscInitialization();

foreach($miscInitialization->getPaginationSingleMiscs() as $miscName => $misc){
    $variablesTV->setValue($miscName, $misc);
}

foreach($miscInitialization->getPaginationDependentMiscs() as $miscName => $mainMiscIds){
    $variablesTV->setValue($miscName, json_encode($mainMiscIds));
}


// Структура документации
$structureDocumentation1 = structure_documentation1Table::getAllActive();  // Производственные/непроизводственные
$NodeStructure1 = new NodeStructure($structureDocumentation1);

$structureDocumentation2 = structure_documentation2Table::getAllActive();  // Линейные
$NodeStructure2 = new NodeStructure($structureDocumentation2);

$variablesTV->setValue('structureDocumentation1', $NodeStructure1->getDepthStructure());
$variablesTV->setValue('structureDocumentation2', $NodeStructure2->getDepthStructure());