<?php


use Lib\Actions\Locator as ActionLocator;
use Lib\Singles\VariableTransfer;

$availableActions = ActionLocator::getInstance()->getActions()->getAccessActions()->getAvailableActions();

var_dump($availableActions);




$availableActionsTV = [];

foreach ($availableActions as $action) {

    $availableActionsTV[] = [

        'ref'   => "/{$action['page_name']}?id_application={$_GET['id_application']}",
        'label' => $action['name']
    ];
}

VariableTransfer::getInstance()->setValue('availableActions', $availableActionsTV);