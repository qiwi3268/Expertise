<?php

$actions = LocatorActions::getInstance()->getActions();

$availableActionsAssoc = $actions->getAvailableActions();

$availableActions = [];

foreach($availableActionsAssoc as $action){

    $availableActions[] = [

        'ref'   => '/'.$action['page_name'].'?'._PROPERTY_IN_APPLICATION['id_application'].'='.$_GET[_PROPERTY_IN_APPLICATION['id_application']],
        'label' => $action['name']
    ];
}

$variablesTV = VariableTransfer::getInstance();

$variablesTV->setValue('availableActions', $availableActions);


