<?php


// Данная страница представляет собой мини-движок по формированию навигационных страниц
// Подключение sidebar'а и view происходит напрямую, поскольку заранее неизвестно, какая view нужна

if(checkParamsGET('b', 'v')){
    
    $G_block = $_GET['b'];
    $G_view = $_GET['v'];
}else{
    // Если отсутствуют параметры в GET-запросе, берем дефолтные для этого пользователя
    $params = ApplicationHelper::getDefaultNavigationPage();
    $G_block = $params['b'];
    $G_view = $params['v'];
}

var_dump($G_block);
var_dump($G_view);


$Navigation = new Navigation(Session::getUserRoles());
$userNavigation = $Navigation->getUserNavigation();

//var_dump($userNavigation);

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


$path_controller = _ROOT_.'/controllers/home/navigation_sidebar.php';
$path_view = _ROOT_.'/views/home/navigation_sidebar.php';

if(!file_exists($path_controller)){
    throw new Exception("Отсутствует controller navigation_sidebar по пути: $path_controller");
}
if(!file_exists($path_view)){
    throw new Exception("Отсутствует view navigation_sidebar по пути: $path_view");
}

// Прокидываем переменные в navigation_sidebar
$variablesTV = VariableTransfer::getInstance();
$variablesTV->setExistenceFlag('isNavigationPage', true);
$variablesTV->setValue('userNavigation', $userNavigation);

require_once $path_controller;

