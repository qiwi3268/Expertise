<?php


$variablesTV = VariableTransfer::getInstance();


// -----------------------------------------------------------------------------------------------------------------
// Зона валидации заполненности блоков анкеты
// -----------------------------------------------------------------------------------------------------------------

// Сведения о цели обращения ------------------------------------- block1_completed
// Обязательные поля: Цель обращения
//                    Предмет экспертизы
//
if($variablesTV->getExistenceFlag('expertise_purpose') &&
   $variablesTV->getExistenceFlag('expertise_subjects')){
    
    $variablesTV->setValue('block1_completed', true);
}else{
    $variablesTV->setValue('block1_completed', false);
}


// Сведения об объекте ------------------------------------------- block2_completed
// Обязательные поля: Наименование объекта
//                    Вид объекта
//                    Функциональное назначение
//                    Вид работ
//                    * если выбран чекбокс "Национальный проект"
//                        Национальный проект
//                        Федеральный проект
//
if($variablesTV->getExistenceFlag('object_name') &&
   $variablesTV->getExistenceFlag('type_of_object') &&
   $variablesTV->getExistenceFlag('functional_purpose') &&
   $variablesTV->getExistenceFlag('functional_purpose_subsector') &&
   $variablesTV->getExistenceFlag('functional_purpose_group') &&
   $variablesTV->getExistenceFlag('type_of_work') &&
   $variablesTV->getExistenceFlag('curator')){
    
    // Если сохранен хоть один пункт из Национального проекта, значит был выбран чекбокс
    if($variablesTV->getExistenceFlag('national_project') ||
       $variablesTV->getExistenceFlag('federal_project') ||
       $variablesTV->getExistenceFlag('date_finish_building')){
        
        if($variablesTV->getExistenceFlag('national_project') && $variablesTV->getExistenceFlag('federal_project')){
            $variablesTV->setValue('block2_completed', true);
        }else{
            $variablesTV->setValue('block2_completed', false);
        }
    }else{
        $variablesTV->setValue('block2_completed', true);
    }
}else{
    $variablesTV->setValue('block2_completed', false);
}
