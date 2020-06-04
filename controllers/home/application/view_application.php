<?php

//todo проверка на корректные гет параметры
$applicationId = $_GET['id_application'];

//todo проверка на доступ пользователя

//todo проверка на существование заявления
//или все эти проверки вынести в отдельный предбанник?
$applicationAssoc = ApplicationsTable::getAssocByIdForView($applicationId);

var_dump($applicationAssoc);

$variablesTV = VariablesToView::getInstance();

// -----------------------------------------------------------------------------------------------------------------
// Зона заполнения данных анкеты singleton'а
// -----------------------------------------------------------------------------------------------------------------

// Цель обращения -----------------------------------------------------------------
//
$expertisePurpose = $applicationAssoc[_PROPERTY_IN_APPLICATION['expertise_purpose']];

if(!is_null($expertisePurpose)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['expertise_purpose'], $expertisePurpose);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose'], false);
}

//

// Предметы экспертизы ------------------------------------------------------------
//
$expertiseSubjects = $applicationAssoc[_PROPERTY_IN_APPLICATION['expertise_subjects']];
if(!is_null($expertiseSubjects)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_subjects'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['expertise_subjects'], $expertiseSubjects);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_subjects'], false);
}


// Дополнительная инфформация -----------------------------------------------------
//
$additionalInformation = $applicationAssoc[_PROPERTY_IN_APPLICATION['additional_information']];
if(!is_null($additionalInformation)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['additional_information'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['additional_information'], $additionalInformation);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['additional_information'], false);
}


// Наименование объекта -----------------------------------------------------------
//
$objectName = $applicationAssoc[_PROPERTY_IN_APPLICATION['object_name']];
if(!is_null($objectName)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['object_name'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['object_name'], $objectName);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['object_name'], false);
}


// Вид объекта --------------------------------------------------------------------
//
$typeOfObject = $applicationAssoc[_PROPERTY_IN_APPLICATION['type_of_object']];
if(!is_null($typeOfObject)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_object'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['type_of_object'], $typeOfObject);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_object'], false);
}


// Функциональное назначение ------------------------------------------------------
//
$functionalPurpose = $applicationAssoc[_PROPERTY_IN_APPLICATION['functional_purpose']];
if(!is_null($functionalPurpose)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['functional_purpose'], $functionalPurpose);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose'], false);
}


// Номер утверждения документации по планировке территории ------------------------
//
$numberPlanningDocumentationApproval = $applicationAssoc[_PROPERTY_IN_APPLICATION['number_planning_documentation_approval']];
if(!is_null($numberPlanningDocumentationApproval)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'], $numberPlanningDocumentationApproval);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'], false);
}


// Дата утверждения документации по планировке территории -------------------------
//
$datePlanningDocumentationApproval = $applicationAssoc[_PROPERTY_IN_APPLICATION['date_planning_documentation_approval']];
if(!is_null($datePlanningDocumentationApproval)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['date_planning_documentation_approval'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['date_planning_documentation_approval'], GetDdMmYyyyDate($datePlanningDocumentationApproval));
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['date_planning_documentation_approval'], false);
}


// Номер ГПЗУ ---------------------------------------------------------------------
//
$numberGPZU = $applicationAssoc[_PROPERTY_IN_APPLICATION['number_GPZU']];
if(!is_null($numberGPZU)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['number_GPZU'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['number_GPZU'], $numberGPZU);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['number_GPZU'], false);
}


// Дата ГПЗУ ----------------------------------------------------------------------
//
$dateGPZU = $applicationAssoc[_PROPERTY_IN_APPLICATION['date_GPZU']];
if(!is_null($dateGPZU)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['date_GPZU'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['date_GPZU'], GetDdMmYyyyDate($dateGPZU));
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['date_GPZU'], false);
}









// -----------------------------------------------------------------------------------------------------------------
// Зона валидации заполненности блоков анкеты
// -----------------------------------------------------------------------------------------------------------------


// Сведения о цели обращения ------------------------------------------------------
// Обязательные поля: Цель обращения
//                    Предмет экспертизы
//
if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose']) &&
   $variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_subjects'])){
    $variablesTV->setValue('block1_completed', true);
}else{
    $variablesTV->setValue('block1_completed', false);
}





