<?php


use Lib\Actions\Locator as ActionLocator;
use Lib\Singles\VariableTransfer;


$availableActions = ActionLocator::getInstance(CURRENT_DOCUMENT_TYPE)
    ->getObject()
    ->getAccessActions()
    ->getAvailableActions();

$test = ActionLocator::getInstance(CURRENT_DOCUMENT_TYPE)->getObject()->getAccessActions();


$availableActionsTV = [];

foreach ($availableActions as $action) {

    $availableActionsTV[] = [

        'ref'   => "/{$action['page_name']}?id_document=" . CURRENT_DOCUMENT_ID,
        'label' => $action['name']
    ];
}

VariableTransfer::getInstance()->setValue('availableActions', $availableActionsTV);