<?php

//var_dump($_SESSION);

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

// Получение данных о номере заявления
//

$variablesTV = VariableTransfer::getInstance();

$variablesTV->setValue('applicationNumericalName', "ЗАЯВЛЕНИЕ НА ЭКСПЕРТИЗУ $appNumName");

$variablesTV->setValue('applicationId', $applicationId);

// -----------------------------------------------------------------------------------------
// Получение справочников
// -----------------------------------------------------------------------------------------

// Справочник "Цель обращения"
$expertisePurposes = misc_expertisePurposeTable::getAllActive();

// Справочник "Предмет экспертизы" -> корреляция с "Цель обращения"
$expertiseSubjects = misc_expertiseSubjectTable::getActive_CORR_ExpertisePurpose($expertisePurposes);

// Справочник "Вид объекта"
$typeOfObjects = misc_typeOfObjectTable::getAllActive();

// Справочник "Функциональное назначение"
$functionalPurposes = misc_functionalPurposeTable::getAllActive();

// Справочник "Функциональное назначение. Подотрасль" -> корреляция с "Функциональное назначение"
$functionalPurposeSubsectors = misc_functionalPurposeSubsectorTable::getActive_CORR_FunctionalPurpose($functionalPurposes);

// Справочник "Функциональное назначение. Группа" -> корреляция с "Функциональное назначение. Подотрасль"
$functionalPurposeGroups = misc_functionalPurposeGroupTable::getActive_CORR_FunctionalPurposeSubsector(misc_functionalPurposeSubsectorTable::getAllActive());

// Справочник "Вид работ" -> корреляция с "Цель обращения"
$typeOfWorks = misc_typeOfWorkTable::getActive_CORR_ExpertisePurpose($expertisePurposes);

// Справочник "Тип объекта культурного наследия"
$culturalObjectTypes = misc_culturalObjectTypeTable::getAllActive();

// Справочник "Национальный проект"
$nationalProjects = misc_nationalProjectTable::getAllActive();

// Справочник "Федеральный проект" -> корреляция с "Национальный проект"
$federalProjects = misc_federalProjectTable::getActive_CORR_NationalProject($nationalProjects);

// Справочник "Куратор"
$curators = misc_curatorTable::getAllActive();


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


var_dump(ApplicationHelper::getPaginationDependentMisc($federalProjects, $paginationSize));



// -----------------------------------------------------------------------------------------
// Установка зависимостей отображения
// -----------------------------------------------------------------------------------------


// Ключ массива - аттрирут "data-row_name" в элементе "body-card__row" - это
// главный элемент, при изменении input hidden'а которого, будет или не будет
// отображаться зависимая строчка
//
// В массиве находятся карты, где:
// ключ - значение input hidden'а главного элемента
// массив, в ключах которых data-row_name строки, которая будет отображаться (true) или скрываться (false)
//

$displayDependencies = [

    // Зависимость от выбранного "Вида объекта"
    _PROPERTY_IN_APPLICATION['type_of_object'] => [
        1 => [_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] => true,
              _PROPERTY_IN_APPLICATION['date_planning_documentation_approval']   => true,
              _PROPERTY_IN_APPLICATION['number_GPZU']                            => false,
              _PROPERTY_IN_APPLICATION['date_GPZU']                              => false
        ],

        2 => [_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] => false,
              _PROPERTY_IN_APPLICATION['date_planning_documentation_approval']   => false,
              _PROPERTY_IN_APPLICATION['number_GPZU']                            => true,
              _PROPERTY_IN_APPLICATION['date_GPZU']                              => true
        ]
    ],

    // Зависимость от ЧЕКБОКСА "Объект культурного наследия"
    _PROPERTY_IN_APPLICATION['cultural_object_type_checkbox'] => [
        0 => [_PROPERTY_IN_APPLICATION['cultural_object_type'] => false],
        1 => [_PROPERTY_IN_APPLICATION['cultural_object_type'] => true]
    ],

    // Зависимость от ЧЕКБОКСА "Национальный проект"
    _PROPERTY_IN_APPLICATION['national_project_checkbox'] => [
        0 => [_PROPERTY_IN_APPLICATION['national_project']     => false,
              _PROPERTY_IN_APPLICATION['federal_project']      => false,
              _PROPERTY_IN_APPLICATION['date_finish_building'] => false
        ],
        1 => [_PROPERTY_IN_APPLICATION['national_project']     => true,
             _PROPERTY_IN_APPLICATION['federal_project']       => true,
             _PROPERTY_IN_APPLICATION['date_finish_building']  => true
        ],
    ],
];

$variablesTV->setValue('displayDependencies', json_encode($displayDependencies));









































