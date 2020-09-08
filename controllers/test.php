<?php


use Lib\Responsible\Responsible;
use Lib\Responsible\XMLReader;
use Tables\user;
use Tables\people_name;
use Tables\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;


$activeExperts = user::getActiveExperts();

foreach ($activeExperts as &$expert) {
    $expert['fio'] = getFIO($expert);
    unset($expert['last_name'], $expert['first_name'],$expert['middle_name']);
}
unset($expert);

var_dump($activeExperts);

