<?php

use Lib\Singles\VariableTransfer;
use Lib\Singles\DocumentTreeHandler;
use Tables\Locators\TypeOfObjectTableLocator;


$VT = VariableTransfer::getInstance();

$treeHandler = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree');
$typeOfObjectTableLocator = new TypeOfObjectTableLocator($treeHandler->getTypeOfObjectId());

// Описание раздела
//
$descriptionTable = $typeOfObjectTableLocator->getDescriptivePartDescription();

$descriptionsTV = [];

if (!is_null($descriptions = $descriptionTable::getAllAssocByIdMainDocument(CURRENT_DOCUMENT_ID))) {

    foreach ($descriptions as $description) {

        $author = getFIO($description, false);

        $descriptionsTV[$author] = $description['description'];
    }
}

vd($descriptionsTV);

$VT->setValue('descriptions', $descriptionsTV);

// Технико-экономические показатели
//
$TEPTable = $typeOfObjectTableLocator->getDescriptivePartTEP();

$TEPsByAuthorsTV = [];

if (!is_null($TEPs = $TEPTable::getAllAssocByIdMainDocument(CURRENT_DOCUMENT_ID))) {

    foreach ($TEPs as $TEP) {

        $author = getFIO($TEP);

        $TEPsByAuthorsTV[$author][] = [
            'indicator' => $TEP['indicator'],
            'value'     => "{$TEP['value']} {$TEP['measure']}",
            'note'      => $TEP['note'] ?? 'Отсутствует'
        ];
    }
}

$VT->setValue('TEPsByAuthors', $TEPsByAuthorsTV);
