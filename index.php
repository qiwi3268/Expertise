<?php

//todo получить вывод варнингов и ошибок в кроне
//todo настроить дебаг крон-скриптов

// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//phpinfo();

require_once 'core/Classes/StartingInitialization.php';
$ini = new StartingInitialization('/var/www/html');
$ini->requireDefinedVariables();
$ini->enableClassAutoloading();
$ini->requireDataBasePack();
$ini->requireWebPack();


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

$benchmark = [];

foreach($route->getRequiredFiles() as $routeRequiredFile){
    
    if(!file_exists($routeRequiredFile['path'])){
        throw new RouteException("Отсутствует {$routeRequiredFile['type']} файл по пути: {$routeRequiredFile['path']}");
    }
    
    $benchmarkTimeStart = microtime(true);
    require_once $routeRequiredFile['path'];
    $benchmark[] = microtime(true) - $benchmarkTimeStart;
};

DataBase::closeDB();

$benchmark['sum'] = array_sum($benchmark);