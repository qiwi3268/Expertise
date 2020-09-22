<?php

use Tables\Docs\Relations\HierarchyTree;
use Lib\Singles\VariableTransfer;


$hierarchyTree = new HierarchyTree(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
$fullTree = $hierarchyTree->getTree();

$availableDocumentsTV = [];



VariableTransfer::getInstance()->setValue('availableDocuments', $availableDocumentsTV);


function addDocumentToArray(
    array &$array,
    string $label,
    string $ref,
    array $description,
    int $depth
): void {

    $array[] = [
        'label' => $label,
        'ref' => $ref,
        'description' => $description,
        'depth' => $depth
    ];
}


