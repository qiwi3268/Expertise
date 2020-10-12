<?php

use core\Classes\Session;
use Lib\DataBase\Transaction;
use Lib\Singles\NodeStructure;
use Lib\Responsible\Responsible;
use Lib\Singles\VariableTransfer;
use Lib\Miscs\Initialization\Initializer as MiscInitializer;
use Classes\Application\Helpers\Helper as ApplicationHelper;
use Tables\Docs\application;
use Tables\application_counter;
use Tables\applicant_access_group;
use Tables\Structures\{
    documentation_1,
    documentation_2
};


$variablesTV = VariableTransfer::getInstance();

$userId = Session::getUserId();

$transaction = new Transaction();

// Инкрементируем и получаем внутренний счетчик заявления
application_counter::incrementInternal();

$internalCounter = application_counter::getInternal();

// Числовое имя
$appNumName = ApplicationHelper::getInternalAppNumName($internalCounter);

$applicationId = application::createTemporary($userId, $appNumName);

// Записываем текущего пользователя в группу доступа "Полный доступ" к заявлению
applicant_access_group::createFullAccess($applicationId, $userId);

// Устанавливаем ответственную группу доступа "Полный доступ"
$responsible = new Responsible($applicationId, DOCUMENT_TYPE['application']);

$responsible->createResponsibleType3($transaction, ['full_access'], false);

$transaction->start();

// Создание директории заявления
if (!mkdir(APPLICATIONS_FILES . "/$applicationId")) {
    exit('Не удалось создать директорию заявления');
}

// Установка прав на папку. Устанавливается отдельно, т.к. на уровне ОС стоит umask
if (!chmod(APPLICATIONS_FILES . "/$applicationId", 0757)) {
    exit('Не удалось задать права на директорию');
}

$variablesTV->setValue('numerical_name', $appNumName);
$variablesTV->setValue('id_application', $applicationId);


// Инициализация справочников
$miscInitializer = new MiscInitializer([
    'expertise_purpose',
    'expertise_subject',
    'type_of_object',
    'functional_purpose',
    'functional_purpose_subsector',
    'functional_purpose_group',
    'type_of_work',
    'cultural_object_type',
    'national_project',
    'federal_project',
    'curator',
    'budget_level'
]);

foreach ($miscInitializer->getPaginationSingleMiscs() as $miscName => $misc) {
    $variablesTV->setValue($miscName, $misc);
}

foreach ($miscInitializer->getPaginationDependentMiscs() as $miscName => $mainMiscIds) {
    $variablesTV->setValue($miscName, json_encode($mainMiscIds));
}


// Структура документации
$structureDocumentation1 = documentation_1::getAllAssocWhereActive();  // Производственные / непроизводственные
$nodeStructure1 = new NodeStructure($structureDocumentation1);

$structureDocumentation2 = documentation_2::getAllAssocWhereActive();  // Линейные
$nodeStructure2 = new NodeStructure($structureDocumentation2);

$variablesTV->setValue('structureDocumentation1', $nodeStructure1->getDepthStructure());
$variablesTV->setValue('structureDocumentation2', $nodeStructure2->getDepthStructure());

var_dump($applicationId);