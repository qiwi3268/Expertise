<?php

// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '/var/www/html/core/defined_variables.php';
require_once ROOT . '/functions/functions.php';

spl_autoload_register(function (string $className) {

    $path = null;

    if (containsAll($className, '\\')) {

        $namespacePath = str_replace('\\', '/', $className);

        $pattern = "/\A(.+)\/(.+\z)/";

        list(1 => $tmp_path, 2 => $tmp_name) = GetHandlePregMatch($pattern, $namespacePath, false);

        $path = ROOT . "/{$tmp_path}/{$tmp_name}.php";
    }

    if (!is_null($path) && file_exists($path)) require_once $path;
});


use core\Classes\Exceptions\Route as RouteEx;
use core\Classes\Route;
use Lib\DataBase\DataBase;


//phpinfo();


session_start();

DataBase::constructDB('ge');

$route = new Route($_SERVER['REQUEST_URI']);

// Запрашиваемая страница не найдена
if (!$route->checkRoute()) {
    //header('Location: /error403');
    var_dump('Роут не найден :(');
    exit();
}

$route->checkRouteCorrect();

$redirect = $route->getRedirect();

if ($redirect) {
    header("Location: /$redirect");
    exit();
}

$route->checkAccess();

define('URN', $route->getURN());


foreach ($route->getRequiredFiles() as $routeRequiredFile) {

    if (!file_exists($routeRequiredFile['path'])) {
        throw new RouteEx("Отсутствует {$routeRequiredFile['type']} файл по пути: {$routeRequiredFile['path']}");
    }

    require_once $routeRequiredFile['path'];
}

DataBase::closeDB();