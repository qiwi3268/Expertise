<?php

use core\Classes\Session;
use Lib\Singles\NodeStructure;
use Lib\Singles\VariableTransfer;
use Classes\Application\Helpers\Helper as ApplicationHelper;
use Tables\applications;
use Tables\application_counter;
use Tables\Structures\{
    documentation_1,
    documentation_2
};


$variablesTV = VariableTransfer::getInstance();

$userId = Session::getUserInfo()['id'];


// Инкрементируем и получаем внутренний счетчик заявления
application_counter::incrementInternal();
$internalCounter = application_counter::getInternal();

// Числовое имя
$appNumName = ApplicationHelper::getInternalAppNumName($internalCounter);

$applicationId = applications::createTemporary($userId, $appNumName);

var_dump($applicationId);

// Создание директории заявления
if (!mkdir(APPLICATIONS_FILES . "/$applicationId")) {
    exit('Не удалось создать директорию заявления');
}

// Установка прав на папку. Устанавливается отдельно, т.к. на уровне ОС стоит umask
if (!chmod(APPLICATIONS_FILES . "/$applicationId", 0757)) {
    exit('Не удалось задать права на директорию');
}

// Добавляем созданное заявление в сессию
Session::addAuthorRoleApplicationId($applicationId);

$variablesTV->setValue('numerical_name', $appNumName);
$variablesTV->setValue('id_application', $applicationId);


// Справочники
$miscInitializator = new \Classes\Application\Miscs\Initialization\CreateFormInitializator();

foreach ($miscInitializator->getPaginationSingleMiscs() as $miscName => $misc) {
    $variablesTV->setValue($miscName, $misc);
}

foreach ($miscInitializator->getPaginationDependentMiscs() as $miscName => $mainMiscIds) {
    $variablesTV->setValue($miscName, json_encode($mainMiscIds));
}


// Структура документации
$structureDocumentation1 = documentation_1::getAllActive();  // Производственные/непроизводственные
$NodeStructure1 = new NodeStructure($structureDocumentation1);

$structureDocumentation2 = documentation_2::getAllActive();  // Линейные
$NodeStructure2 = new NodeStructure($structureDocumentation2);

$variablesTV->setValue('structureDocumentation1', $NodeStructure1->getDepthStructure());
$variablesTV->setValue('structureDocumentation2', $NodeStructure2->getDepthStructure());