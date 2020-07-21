<?php


// Включения вывода ошибок и предупреждений
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//phpinfo();

// Определение общих констант
require_once 'core/defined_variables.php';

// Автозагрузка классов
spl_autoload_register(function(string $className){

    if(mb_stripos($className, 'exception') !== false){          // Исключения
        require_once _ROOT_."/Classes/Exceptions/{$className}.php";

    }elseif(mb_stripos($className,'table') !== false){          // Таблицы
        require_once _ROOT_."/Classes/Tables/{$className}.php";

    }elseif(mb_stripos($className, 'actions') !== false){       // Действия
        require_once _ROOT_."/Classes/Actions/{$className}.php";

    }elseif(mb_stripos($className, 'helper') !== false){        // Вспомогательные классы
        require_once _ROOT_."/Classes/Helpers/{$className}.php";
    }
});

require_once 'core/Classes/DataBase.php';
require_once 'core/Classes/ParametrizedQuery.php';
require_once 'core/Classes/SimpleQuery.php';

require_once 'core/Classes/Session.php';
require_once 'core/Classes/Access.php';
require_once 'core/Classes/Route.php';

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