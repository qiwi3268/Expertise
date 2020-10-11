<?php

use core\Classes\Session;
use Exception as SelfEx;
use Lib\Responsible\Responsible;
use Lib\Singles\PrimitiveValidator;
use Tables\user;
use Tables\people_name;
use Tables\Docs\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;
use Tables\Actions\application as ApplicationActions;
use core\Classes\RoutesXMLHandler;
use Tables\Structures\documentation_1;
use Lib\Singles\Helpers\PageAddress;
use Tables\assigned_expert_total_cc;

use Lib\DataBase\Transaction;
use Tables\test;
use Tables\Locators\DocumentTypeTableLocator;
use Lib\Singles\DocumentationFilesFacade;
use Lib\Miscs\Validation\SingleMisc;



$validator = new PrimitiveValidator();

$comments = [
    [
        'id'                  => null,
        'text'                => 'aaa',
        'normative_document'  => 'bbb',
        'note'                => '',
        'comment_criticality' => '1',
        'no_files'            => null,
        'files'               => ['1', '2', '3']
    ],
    [
        'id'                  => '123',
        'text'                => 'aaa',
        'normative_document'  => 'bbb',
        'note'                => 'ccc',
        'comment_criticality' => '',
        'no_files'            => '1',
        'files'               => []
    ]
];

// Валидация входного массива
//
// В первом обходе
// Во втором обходе проверяется весь массив
foreach ($comments as $comment) {

}
