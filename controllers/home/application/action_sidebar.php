<?php


$actions = \Lib\Actions\Locator::getInstance()->getActions();

$availableActionsAssoc = $actions->getAvailableActions();

$availableActions = [];

foreach ($availableActionsAssoc as $action) {

    $availableActions[] = [

        'ref'   => "/{$action['page_name']}?id_application={$_GET['id_application']}",
        'label' => $action['name']
    ];
}

\Lib\Singles\VariableTransfer::getInstance()->setValue('availableActions', $availableActions);