<?php

use Lib\Miscs\Initialization\Initializer as MiscInitializer;
use Lib\Singles\TemplateMaker;

use Lib\DataBase\Transaction;
use Classes\TotalCC\Actions\DefaultFormParametersAction1;
use Tables\Docs\Relations\ParentDocumentLinker;
use Classes\Application\FinancingSources;


$linker = new ParentDocumentLinker(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
$applicationId = $linker->getApplicationId();



$transaction = new Transaction();


$commonPartApplicantDetails = [
  'class'  => '\Tables\common_part_applicant_details',
  'method' => 'getAssocByIdTotalCC'
];

$transaction->add(
    $commonPartApplicantDetails['class'],
    $commonPartApplicantDetails['method'],
    [8]
);


$financingSources = new FinancingSources($applicationId);
$transaction->add($financingSources, 'getFinancingSources');




$defaultParameters = new DefaultFormParametersAction1(
    $transaction,
    [
        'applicant_details' => $commonPartApplicantDetails,
        'financing_sources' => [
            'class'  => '\\' . get_class($financingSources),
            'method' => 'getFinancingSources'
        ]
    ]
);

// Инициализация справочников
$miscInitializer = new MiscInitializer([
    'budget_level'
]);

$singleMiscs = $miscInitializer->getPaginationSingleMiscs();


$defaultParameters = $defaultParameters->getDefaultParameters();


// Регистритуем данные для шаблона финансовых источников
TemplateMaker::registration(
    'edit_financing_sources',
    TemplateMaker::HOME_WITH_DATA_EDIT . 'financing_sources.php',
    [
        'budget_level'      => $singleMiscs['budget_level'],
        'financing_sources' => $defaultParameters['financing_sources']
    ]
);

TemplateMaker::registration(
    'create_financing_sources',
    TemplateMaker::HOME_WITH_DATA_CREATE . 'financing_sources.php',
    [
        'budget_level' => $singleMiscs['budget_level'],
    ]
);

//TemplateMaker::registration();

//$VT->setValue('defaultParameters', );

//var_dump($VT->getValue('defaultParameters'));