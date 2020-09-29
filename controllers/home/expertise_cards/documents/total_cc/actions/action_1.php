<?php


use Lib\Singles\VariableTransfer;
use Lib\DataBase\Transaction;
use Classes\TotalCC\Actions\DefaultFormParametersAction1;



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







$defaultParameters = new DefaultFormParametersAction1(
    $transaction,
    [
        'applicantDetails' => $commonPartApplicantDetails
    ]
);


$VT = VariableTransfer::getInstance();
$VT->setValue('defaultParameters', $defaultParameters->getDefaultParameters());

var_dump($VT->getValue('defaultParameters'));