<?php

define('_ROOT_', '/var/www/html/');

require_once _ROOT_.'core/Classes/DataBase.php';
require_once _ROOT_.'Classes/Exceptions/DataBaseException';

require_once _ROOT_.'core/Classes/ParametrizedQuery.php';
require_once _ROOT_.'core/Classes/SimpleQuery.php';

DataBase::constructDB('ge');


