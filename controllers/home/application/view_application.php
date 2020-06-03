<?php

//todo проверка на корректные гет параметры
$applicationId = $_GET['id_application'];

//todo проверка на доступ пользователя

//todo проверка на существование заявления
//или все эти проверки вынести в отдельный предбанник?
$applicationAssoc = ApplicationsTable::getAssocByIdForView($applicationId);

var_dump($applicationAssoc);

$variablesTV = VariablesToView::getInstance();

$expertisePurpose = $applicationAssoc[_PROPERTY_IN_APPLICATION['expertise_purpose']];

if(!is_null($expertisePurpose)){

    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose'], true);
    $variablesTV->setValue(_PROPERTY_IN_APPLICATION['expertise_purpose'], $expertisePurpose);
}else{

    $variablesTV->setExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose'], false);
}

$test = 1;
