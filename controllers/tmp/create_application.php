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

// Справочник "Цель экспертизы"
$expertisePurposes = misc_expertisePurposeTable::getAllActive();

// Справочник "Предмет экспертизы" -> корреляция с "Цель экспертизы"
$expertiseSubjects = misc_expertiseSubjectTable::getActive_CORR_ExpertisePurpose($expertisePurposes);

// Справочник "Вид работ" -> корреляция с "Цель экспертизы"
$typeOfWorks = misc_typeOfWorkTable::getActive_CORR_ExpertisePurpose($expertisePurposes);

// Справочник "Функциональное назначение"
$functionalPurposes = misc_functionalPurposeTable::getAllActive();

// Справочник "Вид объекта"
$typeOfObjects = misc_typeOfObjectTable::getAllActive();


//------------------------------------------------------
$userFioTV = GetUserFIO($userInfo);
$appNumNameTV = "ЗАЯВЛЕНИЕ НА ЭКСПЕРТИЗУ $appNumName";

//------------------------------------------------------
$paginationSize = 5;

$expertisePurposesTV = array_chunk($expertisePurposes, $paginationSize);
$expertiseSubjectsTV = array_chunk($expertiseSubjects, $paginationSize);
$expertiseSubjectsIH = json_encode($expertiseSubjects);

$typeOfWorksIH = $typeOfWorks;

// Т.к. виды работ упакованы по id целей обращений
foreach($typeOfWorksIH AS &$purpose){
    $purpose = array_chunk($purpose, $paginationSize);
}
unset($purpose);


$typeOfWorksIH = json_encode($typeOfWorksIH);


$functionalPurposesTV = array_chunk($functionalPurposes, $paginationSize);


//------------------- Зависимости отображений объектов ---------------------


// Ключ массива - аттрирут "data-row_name" в элементе "body-card__row" - это
// главный элемент, при изменении input hidden'а которого, будет или не будет
// отображаться зависимая строчка
//
// В массиве находятся карты, где:
// ключ - значение input hidden'а главного элемента
// массив, в ключах которых data-row_name строки, которая будет отображаться (true) или скрываться (false)
//

$displayDependencies = [

    'expertise_subject' => [

        1 => [
            'estimate_cost'      => false,
            'functional_purpose' => true
        ],

        2 => [
            'estimate_cost'      => false,
            'functional_purpose' => true
        ],

        3 => [
            'estimate_cost'      => true,
            'functional_purpose' => false
        ],
    ],
];

$displayDependenciesIH = json_encode($displayDependencies);









































