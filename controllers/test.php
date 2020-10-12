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

use Tables\Helpers\Helper;
use Lib\DataBase\Transaction;
use Tables\test;
use Tables\Locators\DocumentTypeTableLocator;
use Lib\Singles\DocumentationFilesFacade;
use Lib\Miscs\Validation\SingleMisc;
use Lib\Actions\ExecutionActionsResult;

$params = [
    'test' => 1,
    'test2' => NULL,
    'test3' => 3,
    'test4' => NULL,
    'tuc' => 0,
    'lala' => NULL
];

$a = Helper::getValuesWithoutNullForUpdate($params);

vd($a);
