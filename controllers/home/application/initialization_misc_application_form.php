<?php


$variablesTV = VariableTransfer::getInstance();

// -----------------------------------------------------------------------------------------
// Получение справочников
// -----------------------------------------------------------------------------------------


$expertisePurposes = misc_expertisePurposeTable::getAllActive();


$expertiseSubjects = misc_expertiseSubjectTable::getActive_CORR_ExpertisePurpose($expertisePurposes);


$typeOfObjects = misc_typeOfObjectTable::getAllActive();


$functionalPurposes = misc_functionalPurposeTable::getAllActive();


$functionalPurposeSubsectors = misc_functionalPurposeSubsectorTable::getActive_CORR_FunctionalPurpose($functionalPurposes);

// Справочник "Функциональное назначение. Группа" -> корреляция с "Функциональное назначение. Подотрасль"
$functionalPurposeGroups = misc_functionalPurposeGroupTable::getActive_CORR_FunctionalPurposeSubsector(misc_functionalPurposeSubsectorTable::getAllActive());


$typeOfWorks = misc_typeOfWorkTable::getActive_CORR_ExpertisePurpose($expertisePurposes);


$culturalObjectTypes = misc_culturalObjectTypeTable::getAllActive();


$nationalProjects = misc_nationalProjectTable::getAllActive();


$federalProjects = misc_federalProjectTable::getActive_CORR_NationalProject($nationalProjects);


$curators = misc_curatorTable::getAllActive();



<<<<<<< HEAD
$test = new MiscInitialization();


//var_dump($test->getPaginationSingleMisc());

var_dump($test->getPaginationDependentMisc());
=======
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb


// -----------------------------------------------------------------------------------------
// Разбивка одиночных справочников для пагинации
// -----------------------------------------------------------------------------------------

// Количество справочных элементов на странице
$paginationSize = 8;


$expertisePurposes = array_chunk($expertisePurposes, $paginationSize);      // "Цель обращения"
$variablesTV->setValue('expertisePurposes', $expertisePurposes);

$typeOfObjects = array_chunk($typeOfObjects, $paginationSize);              // "Вид объекта"
$variablesTV->setValue('typeOfObjects', $typeOfObjects);

$functionalPurposes = array_chunk($functionalPurposes, $paginationSize);    // "Функциональное назначение"
$variablesTV->setValue('functionalPurposes', $functionalPurposes);

$culturalObjectTypes = array_chunk($culturalObjectTypes, $paginationSize);  // "Тип объекта культурного наследия"
$variablesTV->setValue('culturalObjectTypes', $culturalObjectTypes);

$nationalProjects = array_chunk($nationalProjects, $paginationSize);        // "Национальный проект"
$variablesTV->setValue('nationalProjects', $nationalProjects);

$curators = array_chunk($curators, $paginationSize);                        // "Куратор"
$variablesTV->setValue('curators', $curators);


// -----------------------------------------------------------------------------------------
// Разбивка зависимых справочников для пагинации
// -----------------------------------------------------------------------------------------


// Справочник "Предмет экспертизы", упакованные по id "Цель обращения"
$variablesTV->setValue('expertiseSubjects', json_encode($expertiseSubjects));

// Справочник "Вид работ", упакованный по id "Цель обращения"
$variablesTV->setValue('typeOfWorks', json_encode(ApplicationHelper::getPaginationDependentMisc($typeOfWorks, $paginationSize)));

// Справочник "Функциональное назначение. Подотрасль", упакованный по id "Функциональное назначение"
$variablesTV->setValue('functionalPurposeSubsectors', json_encode(ApplicationHelper::getPaginationDependentMisc($functionalPurposeSubsectors, $paginationSize)));

// Справочник "Функциональное назначение. Группа", упакованный по id "Функциональное назначение. Подотрасль"
$variablesTV->setValue('functionalPurposeGroups', json_encode(ApplicationHelper::getPaginationDependentMisc($functionalPurposeGroups, $paginationSize)));

// Справочник "Федеральный проект", упакованный по id "Национальный проект"
$variablesTV->setValue('federalProjects', json_encode(ApplicationHelper::getPaginationDependentMisc($federalProjects, $paginationSize)));