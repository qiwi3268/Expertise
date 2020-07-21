<?php


$variablesTV = VariableTransfer::getInstance();


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
