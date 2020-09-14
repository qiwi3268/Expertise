<?php

use core\Classes\Session;
use Exception as SelfEx;
use Lib\Responsible\Responsible;
use Lib\Singles\PrimitiveValidator;
use Tables\user;
use Tables\people_name;
use Tables\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;
use Tables\Actions\application as ApplicationActions;
use core\Classes\RoutesXMLHandler;

$handler = new RoutesXMLHandler();

$page = $handler->getPage('home/application/create22');

$handler->validatePagesStructure();
$handler->handleValidatedPageValues($page);




