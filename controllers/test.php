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


$experts = [
    [
        'id' => 1,
        'ids_main_block_341' => [1,2,3]
    ],
    [
        'id' => 2,
        'ids_main_block_341' => [3,4]
    ],
    [
        'id' => 3,
        'ids_main_block_341' => [1]
    ],
    [
        'id' => 4,
        'ids_main_block_341' => [1, 3, 5]
    ]
];

$blocks = [];

foreach ($experts as $expert) {

    foreach ($expert['ids_main_block_341'] as $id_block) {

        $blocks[$id_block][] = $expert['id'];
    }
}

var_dump($blocks);




