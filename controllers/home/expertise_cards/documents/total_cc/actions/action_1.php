<?php


use Lib\Singles\VariableTransfer;
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
        'applicantDetails' => $commonPartApplicantDetails,
        'financingSources' => [
            'class'  => '\\' . get_class($financingSources),
            'method' => 'getFinancingSources'
        ]
    ]
);


$VT = VariableTransfer::getInstance();
$VT->setValue('defaultParameters', $defaultParameters->getDefaultParameters());

var_dump($VT->getValue('defaultParameters'));