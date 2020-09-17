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

$doc = documentation_1::getAllAssocWhereActiveAndId341NN();

$test = arrayEntry($doc, 'is_active', '1');

$a = [1,2,3];



var_dump($a === [1,2,3]);




