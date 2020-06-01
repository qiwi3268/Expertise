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

// -----------------------------------------------------------------------------------------
// Получение данных о пользователе и номере заявления
// -----------------------------------------------------------------------------------------

$userFioTV = GetUserFIO($userInfo);
$appNumNameTV = "ЗАЯВЛЕНИЕ НА ЭКСПЕРТИЗУ $appNumName";

// -----------------------------------------------------------------------------------------
// Получение справочников
// -----------------------------------------------------------------------------------------

// Справочник "Цель обращения"
$expertisePurposes = misc_expertisePurposeTable::getAllActive();

// Справочник "Предмет экспертизы" -> корреляция с "Цель экспертизы"
$expertiseSubjects = misc_expertiseSubjectTable::getActive_CORR_ExpertisePurpose($expertisePurposes);

// Справочник "Вид объекта"
$typeOfObjects = misc_typeOfObjectTable::getAllActive();

// Справочник "Функциональное назначение"
$functionalPurposes = misc_functionalPurposeTable::getAllActive();

// Справочник "Вид работ" -> корреляция с "Цель экспертизы"
$typeOfWorks = misc_typeOfWorkTable::getActive_CORR_ExpertisePurpose($expertisePurposes);


// -----------------------------------------------------------------------------------------
// Разбивка справочников для пагинации
// -----------------------------------------------------------------------------------------

// Количество справочных элементов на странице
$paginationSize = 5;

$expertisePurposesTV = array_chunk($expertisePurposes, $paginationSize);   // Цель обращения
$typeOfObjectsTV = array_chunk($typeOfObjects, $paginationSize);           // Вид работ
$functionalPurposesTV = array_chunk($functionalPurposes, $paginationSize); // Функциональное назначение


//todo тут дальше смотреть
$typeOfWorksIH = $typeOfWorks;

// Т.к. виды работ упакованы по id целей обращений
foreach($typeOfWorksIH AS &$purpose){
    $purpose = array_chunk($purpose, $paginationSize);
}
unset($purpose);

$expertiseSubjectsIH = json_encode($expertiseSubjects);                    // Предметы экспертизы
$typeOfWorksIH = json_encode($typeOfWorksIH);





// -----------------------------------------------------------------------------------------
// Разбивка справочников для пагинации
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

    // 1 - при
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
    ]
];

$displayDependenciesIH = json_encode($displayDependencies);









































