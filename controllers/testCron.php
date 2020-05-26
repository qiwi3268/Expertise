<?php

//$fileFolder = '/var/www/html/logs/test_logs';
//file_put_contents($fileFolder, "добавляю новую запись в файл \n", FILE_APPEND | LOCK_EX);
//var_dump(file_get_contents($fileFolder));

define('_ROOT_', '/var/www/html/');

require_once _ROOT_.'core/Classes/DataBase.php';

require_once  _ROOT_.'core/Classes/SimpleQuery.php';
require_once _ROOT_.'core/Classes/ParametrizedQuery.php';

require_once _ROOT_.'Classes/Tables/TestTable.php';

DataBase::constructDB('ge');

TestTable::create();

DataBase::closeDB();
