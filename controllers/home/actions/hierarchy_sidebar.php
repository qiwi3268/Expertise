<?php


use Tables\Docs\Relations\HierarchyTree;
use Lib\Singles\VariableTransfer;


$hierarchyTree = new HierarchyTree(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
$fullTree = $hierarchyTree->getTree();

var_dump($fullTree);

$availableDocumentsTV = [];

//todo продумать как рпроверять доступ к текущему документу, с учетом того, что он уже проверен на уровне ядра
//todo также на уровне ядра проверять доступ если от сводника - то и на заявление

$checked = [
    DOCUMENT_TYPE['application'] => false,
    DOCUMENT_TYPE['total_cc'] => false,
    DOCUMENT_TYPE['section_documentation_1'] => false,
    DOCUMENT_TYPE['section_documentation_2'] => false
];

switch (CURRENT_DOCUMENT_TYPE) {
    case DOCUMENT_TYPE['application'] :

        $checked[DOCUMENT_TYPE['application']] = true;
        break;
    case DOCUMENT_TYPE['total_cc'] :

        $checked[DOCUMENT_TYPE['application']] = true;
        $checked[DOCUMENT_TYPE['total_cc']] = true;
        break;
}

// Заявление
// добавляем без дополнительных проверок, т.к. на уровне route callbacks был проверен текущий документ,
// а значит заявление априори доступно
addDocumentToArray(
    $availableDocumentsTV,
    'Заявление',
    '/home/application/view?id_document=' . CURRENT_DOCUMENT_ID,
    ['стадия', 'еще что-то'],
    0
);

// todo договор

// todo счет


if (CURRENT_DOCUMENT_TYPE == DOCUMENT_TYPE['total_cc']) {

}






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


