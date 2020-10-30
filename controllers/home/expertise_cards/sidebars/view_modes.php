<?php


use Lib\Singles\VariableTransfer;
use Lib\ViewModes\ViewModes;
use Lib\Singles\Helpers\PageAddress;


$VT = VariableTransfer::getInstance();

$viewModes = ViewModes::getInstance();

$availableModesTV = [];

foreach ($viewModes->getAvailableViewModes() as $mode) {

    $availableModesTV[] = [
        'label' => $mode['label'],
        'ref'   => PageAddress::createCardRef(CURRENT_DOCUMENT_ID, CURRENT_DOCUMENT_TYPE, $mode['name'])
    ];
}

$VT->setValue('available_view_modes', $availableModesTV);