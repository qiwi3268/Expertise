<?php


$variablesTV = VariableTransfer::getInstance();

// Вызван ли данный файл из навигационной страницы
// (существуют страницы, в которых есть этот sidebar, но они не /home/navigation)
$isNavigationPage = $variablesTV->getExistenceFlag('isNavigationPage%S');

if(is_null($isNavigationPage)){
    $isNavigationPage = false;
}

if($isNavigationPage){
    $userNavigation = $variablesTV->getValue('userNavigation');
}else{
    $Navigation = new Navigation(Session::getUserRoles());
    $userNavigation = $Navigation->getUserNavigation();
}

//todo - проверка на реализацию интерфейса где происходит?

// Получение параметров навигационной страницы
if(checkParamsGET('b', 'v')){
    $G_block = $_GET['b'];
    $G_view = $_GET['v'];
}else{ // Дефолтная страница
    $params = ApplicationHelper::getDefaultNavigationPage();
    $G_block = $params['b'];
    $G_view = $params['v'];
}

// Формирование навигационного массива для view
$navigationBlocksTV = [];

foreach($userNavigation as $block){
    
    $tmp_block = [];
    $tmp_block['label'] = $block['label'];
    
    foreach($block['views'] as $view){
    
        $tmp_section = [];
        $tmp_section['label'] = $view['label'];
        $tmp_section['ref'] = "/home/navigation?b={$block['name']}&v={$view['name']}";
        //todo сделать счетчики
        $tmp_section['counter'] = 0;
        $tmp_section['is_selected'] = (($G_block == $block['name']) && ($G_view == $view['name'])) ? true : false;
        $tmp_block['sections'][] = $tmp_section;
    }
    
    if(!isset($block['refs'])){
        $navigationBlocksTV[] = $tmp_block;
        continue;
    }
    
    foreach($block['refs'] as $ref){
        
        $tmp_section['label'] = $ref['label'];
        $tmp_section['ref'] = $ref['value'];
        $tmp_section['counter'] = false;
        //todo проверить на корректную работу с учетом /
        $tmp_section['is_selected'] = ($ref['value'] == _URN_) ? true : false;
        $tmp_block['sections'][] = $tmp_section;
    }
    
    $navigationBlocksTV[] = $tmp_block;
}

//var_dump($navigationBlocksTV);

$variablesTV->setValue('navigationBlocks', $navigationBlocksTV);

if($isNavigationPage){
    // Проверка на существование view файла была выполнена ранее
    require_once _ROOT_.'/views/home/navigation_sidebar.php';
}
