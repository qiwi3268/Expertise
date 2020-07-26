<?php

//todo получить вывод варнингов и ошибок в кроне
//todo настроить дебаг крон-скриптов

// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//phpinfo();

require_once 'core/Classes/StartingInitialization.php';
$initializator = new StartingInitialization('/var/www/html');
$initializator->requireDefinedVariables();
$initializator->enableClassAutoloading();
$initializator->requireDataBasePack();
$initializator->requireWebPack();


require_once 'functions/functions.php';


session_start();

DataBase::constructDB('ge');

$route = new Route($_SERVER['REQUEST_URI']);

// Запрашиваемая страница не найдена
if(!$route->checkRoute()){
    //header('Location: /error403');
    var_dump('Роут не найден :(');
    exit();
}

$route->checkRouteCorrect();

$redirect = $route->getRedirect();

if($redirect){
    header("Location: /$redirect");
    exit();
}

$route->checkAccess();

define('_URN_', $route->getURN());

foreach($route->getRequiredFiles() as $file){

    if(!file_exists($file['path'])){
        throw new RouteException("Отсутствует {$file['type']} файл по пути: {$file['path']}");
    }
    require_once $file['path'];
};

DataBase::closeDB();