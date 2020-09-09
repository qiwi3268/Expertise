<?php


$variablesTV = \Lib\Singles\VariableTransfer::getInstance();

$applicationId = $_GET['id_document'];
$applicationAssoc = \Tables\application::getAssocById($applicationId);

//var_dump($applicationAssoc);


// -----------------------------------------------------------------------------------------------------------------
// Зона заполнения данных анкеты singleton'а
// -----------------------------------------------------------------------------------------------------------------

$miscNames = MiscInitialization::getMiscNames();

$applicationAssocTV = $applicationAssoc;

// Преобразование дат к строкам todo тоже переделать
if(!is_null($applicationAssocTV['date_planning_documentation_approval'])){
    $applicationAssocTV['date_planning_documentation_approval'] = getDdMmYyyyDate($applicationAssocTV['date_planning_documentation_approval']);
}
if(!is_null($applicationAssocTV['date_GPZU'])){
    $applicationAssocTV['date_GPZU'] = getDdMmYyyyDate($applicationAssocTV['date_GPZU']);
}
if(!is_null($applicationAssocTV['date_finish_building'])){
    $applicationAssocTV['date_finish_building'] = getDdMmYyyyDate($applicationAssocTV['date_finish_building']);
}


// Заполнение сохраненных в заявлении данных
foreach($applicationAssocTV as $property => $value){
    
    if(is_null($value)){
    
        $variablesTV->setExistenceFlag($property, false);
        continue;
    }
    
    $variablesTV->setExistenceFlag($property, true);
    $variablesTV->setValue($property, $value);
}


$miscInitialization = new MiscInitializationEditForm($applicationAssoc);

// Заполнение одиночных справочников
foreach($miscInitialization->getSingleMiscsIncludeInactive() as $miscName => $misc){
    $variablesTV->setValue($miscName, $misc);
}

// Заполнение зависимых справочников
foreach($miscInitialization->getDependentMiscsIncludeInactive() as $miscName => $mainMiscIds){
    $variablesTV->setValue($miscName, json_encode($mainMiscIds));
}




// Цель обращения -----------------------------------------------------------------
//
$expertisePurpose = $applicationAssoc[_PROPERTY_IN_APPLICATION['expertise_purpose']];

if(!is_null($expertisePurpose)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['expertise_purpose'], $expertisePurpose);
    $variablesTV->setValue(_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_expertise_purpose'], $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_expertise_purpose']]);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose'], false);
}


// Предметы экспертизы ------------------------------------------------------------
//
$expertiseSubjects = $applicationAssoc[_PROPERTY_IN_APPLICATION['expertise_subjects']];
if(!is_null($expertiseSubjects)){
    
    // Выносим name и id из подмассивов
    $tmpIds = [];
    $tmpNames = [];
    
    foreach($expertiseSubjects as ['id' => $id, 'name' => $name]){
        $tmpIds[] = $id;
        $tmpNames[] = $name;
    }
    
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_subjects'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['expertise_subjects'], $tmpNames);
    //$variablesTV->setValue(_COLUMN_NAME_IN_APPLICATIONS_TABLE['JSON_id_expertise_subjects'], json_encode($tmpIds));
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
    $variablesTV->setValue(_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_object'], $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_object']]);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_object'], false);
}


// Функциональное назначение ------------------------------------------------------
//
$functionalPurpose = $applicationAssoc[_PROPERTY_IN_APPLICATION['functional_purpose']];
if(!is_null($functionalPurpose)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['functional_purpose'], $functionalPurpose);
    $variablesTV->setValue(_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose'], $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose']]);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose'], false);
}


// Функциональное назначение. Подотрасль ------------------------------------------
//
$functionalPurposeSubsector = $applicationAssoc[_PROPERTY_IN_APPLICATION['functional_purpose_subsector']];
if(!is_null($functionalPurposeSubsector)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose_subsector'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['functional_purpose_subsector'], $functionalPurposeSubsector);
    $variablesTV->setValue(_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose_subsector'], $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose_subsector']]);
}else{
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose_subsector'], false);
}


// Функциональное назначение. Группа ----------------------------------------------
//
$functionalPurposeGroup = $applicationAssoc[_PROPERTY_IN_APPLICATION['functional_purpose_group']];
if(!is_null($functionalPurposeGroup)){
    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose_group'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['functional_purpose_group'], $functionalPurposeGroup);
    $variablesTV->setValue(_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose_group'], $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose_group']]);
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
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['date_planning_documentation_approval'], getDdMmYyyyDate($datePlanningDocumentationApproval));
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
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['date_GPZU'], getDdMmYyyyDate($dateGPZU));
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



// Реорганизация справочников
