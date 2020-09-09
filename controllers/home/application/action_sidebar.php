<?php


use Lib\Actions\Locator as ActionLocator;
use Lib\Singles\VariableTransfer;

$availableActions = ActionLocator::getInstance()->getActions()->getAccessActions()->getAvailableActions();




$availableActionsTV = [];

foreach ($availableActions as $action) {

    $availableActionsTV[] = [

        'ref'   => "/{$action['page_name']}?id_document={$_GET['id_document']}",
        'label' => $action['name']
    ];
}

VariableTransfer::getInstance()->setValue('availableActions', $availableActionsTV);