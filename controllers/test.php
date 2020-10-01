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
use Classes\Application\FinancingSources;
use Lib\DataBase\Transaction;
use Tables\test;
use Classes\Application\Miscs\Initialization\CreateFormInitializer;
use Lib\Singles\TemplateMaker;
use Lib\Miscs\Initialization\Initializer2;

$a = new Initializer2([
    '\Tables\Miscs\type_of_object',
    'functional_purpose',
    'functional_purpose_subsector'
]);

$b = $a->getPaginationSingleMiscs();
$c = $a->getPaginationDependentMiscs();

var_dump($c);
