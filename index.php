<?php

// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//phpinfo();

require_once '/var/www/html/core/defined_variables.php';
require_once ROOT . '/functions/functions.php';

enableAutoloadRegister();

use core\Classes\Route;
use Lib\DataBase\DataBase;
use core\Classes\Request\HttpRequest;

// Инициализация класса для объявления общедоступных констант
HttpRequest::getInstance();

$route = new Route();

// Запрашиваемая страница не найдена
if (!$route->isRouteExist()) {
    //header('Location: /error403');
    var_dump('Роут не найден :(');
    exit();
}

DataBase::constructDB('ge');
session_start();
$route->validatePageStructure()->handleValidatedPageValues();
DataBase::closeDB();