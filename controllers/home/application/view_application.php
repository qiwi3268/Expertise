<?php

//todo проверка на корректные гет параметры
$applicationId = $_GET['id_application'];

//todo проверка на доступ пользователя

//todo проверка на существование заявления
//или все эти проверки вынести в отдельный предбанник?
$applicationAssoc = ApplicationsTable::getAssocByIdForView($applicationId);

var_dump($applicationAssoc);

$variablesTV = VariableTransfer::getInstance();


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


// Функциональное назначение. Подотрасль ------------------------------------------
//
$functionalPurposeSubsector = $applicationAssoc[_PROPERTY_IN_APPLICATION['functional_purpose_subsector']];
if(!is_null($functionalPurposeSubsector)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose_subsector'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['functional_purpose_subsector'], $functionalPurposeSubsector);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose_subsector'], false);
}


// Функциональное назначение. Группа ----------------------------------------------
//
$functionalPurposeGroup = $applicationAssoc[_PROPERTY_IN_APPLICATION['functional_purpose_group']];
if(!is_null($functionalPurposeGroup)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose_group'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['functional_purpose_group'], $functionalPurposeGroup);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose_group'], false);
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


// Вид работ ----------------------------------------------------------------------
//
$typeOfWork = $applicationAssoc[_PROPERTY_IN_APPLICATION['type_of_work']];
if(!is_null($typeOfWork)){
   $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_work'], true);
   $variablesTV->setValue(_PROPERTY_IN_APPLICATION['type_of_work'], $typeOfWork);
}else{
   $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_work'], false);
}


// Кадастровый номер земельного участка -------------------------------------------
//
$cadastralNumber = $applicationAssoc[_PROPERTY_IN_APPLICATION['cadastral_number']];
if(!is_null($cadastralNumber)){
   $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['cadastral_number'], true);
   $variablesTV->setValue(_PROPERTY_IN_APPLICATION['cadastral_number'], $cadastralNumber);
}else{
   $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['cadastral_number'], false);
}











// -----------------------------------------------------------------------------------------------------------------
// Зона валидации заполненности блоков анкеты
// -----------------------------------------------------------------------------------------------------------------


// Сведения о цели обращения ------------------------------------- block1_completed
// Обязательные поля: Цель обращения
//                    Предмет экспертизы
//
if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose']) &&
   $variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_subjects'])){

    $variablesTV->setValue('block1_completed', true);
}else{
    $variablesTV->setValue('block1_completed', false);
}



// Сведения об объекте ------------------------------------------- block2_completed
// Обязательные поля: Наименование объекта
//                    Вид объекта
//                    Функциональное назначение
//                    Вид работ
//
if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['object_name']) &&
   $variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_object']) &&
   $variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose']) &&
   $variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_work'])){

   $variablesTV->setValue('block2_completed', true);
}else{
   $variablesTV->setValue('block2_completed', false);
}




