<?php


$variablesTV = VariableTransfer::getInstance();

$applicationAssoc = ApplicationsTable::getAssocById($_GET['id_application']);

// Преобразование дат к строкам
UpdateDatesTimestampToDdMmYyyy($applicationAssoc,
                        'date_planning_documentation_approval',
                                           'date_GPZU',
                                           'date_finish_building');

// Заполнение сохраненных в заявлении данных
foreach($applicationAssoc as $property => $value){
    
    if(is_null($value)){
        
        $variablesTV->setExistenceFlag($property, false);
        continue;
    }
    
    $variablesTV->setExistenceFlag($property, true);
    $variablesTV->setValue($property, $value);
}