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
use Classes\Application\Files\Initialization\DocumentationFilesFacade;
use Lib\Miscs\Validation\SingleMisc;
use Lib\Actions\ExecutionActionsResult;

$result = [
    'Всего' => [
        'count' => 6,
        'data'  => [
            0 => 1,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 1,
            5 => 1
        ]
    ],
    'Активные' => [
        'count' => 1,
        'data'  => [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 1
        ]
    ]
];

$a = new StatisticDiagram(1);
$a->addColumn('test', 500);
$b = $a->getDiagram();
vd($b);










