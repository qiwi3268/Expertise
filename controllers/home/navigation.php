<?php


// Данная страница представляет собой мини-движок по формированию навигационных страниц
// Подключение sidebar'а и view происходит напрямую, поскольку заранее неизвестно, какая view нужна

// Получение параметров навигационной страницы
list('b' => $G_block, 'v' => $G_view) = checkParamsGET('b', 'v') ? $_GET : ApplicationHelper::getDefaultNavigationPage();

$Navigation = new Navigation(Session::getUserRoles());
$userNavigation = $Navigation->getUserNavigation();


// Проверка на то, что указанная в GET-параметрах навигационная страница существует в навигации пользователя
$blockIsset = false;
$viewIsset = false;

foreach($userNavigation as $block){
    
    if($block['name'] != $G_block) continue;
    
    $blockIsset = true;
    
    foreach($block['views'] as $view){
        
        if($view['name'] != $G_view) continue;
        
        $viewIsset = true;
        break 2;
    }
    break;
}

if(!$blockIsset || !$viewIsset){
    if(!$blockIsset){
        throw new Exception("Указанный навигационный block: '$G_block' не существует для данного пользователя");
    }else{
        throw new Exception("Указанная навигационная view: '$G_view' не существует для данного пользователя");
    }
}


$path_sidebar_controller = _ROOT_.'/controllers/home/navigation_sidebar.php';
$path_sidebar_view = _ROOT_.'/views/home/navigation/navigation_sidebar.php';

if(!file_exists($path_sidebar_controller)){
    throw new Exception("Отсутствует controller navigation_sidebar по пути: '{$path_sidebar_controller}'");
}
if(!file_exists($path_sidebar_view)){
    throw new Exception("Отсутствует view navigation_sidebar по пути: '{$path_sidebar_view}'");
}

// Прокидываем переменные в navigation_sidebar
$variablesTV = VariableTransfer::getInstance();
$variablesTV->setExistenceFlag('isNavigationPage', true);
$variablesTV->setValue('userNavigation', $userNavigation);

// Поключаем контроллер, который внутри себя подключает своё view
require_once $path_sidebar_controller;

// Получаем блок и view текущей страницы. NB NV - navigation block/name
$NB = array_filter($userNavigation, fn($block) => ($block['name'] == $G_block));
$NV = array_filter(array_shift($NB)['views'], fn($view) => ($view['name'] == $G_view));

var_dump($NV);

list('class_name' => $class, 'view_name' => $name) = array_shift($NV);

// Подключаем основное view
$variablesTV->setValue('navigationData', $class::getAssoc(Session::getUserId()));
require_once _ROOT_."/views/home/navigation/{$name}.php";