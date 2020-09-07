<?php


use Lib\Responsible\Responsible;
use Lib\Responsible\XMLReader;
use Tables\user;
use Tables\people_name;
use Tables\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;


$responsible = new Responsible(613, 'application');

$responsible->createNewResponsibleType3( 'only_view', 'full_access');

$test = $responsible->isUserResponsible(1);

var_dump($test);




