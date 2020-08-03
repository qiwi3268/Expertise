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

// Получение параметров навигационной страницы
list('b' => $G_block, 'v' => $G_view) = checkParamsGET('b', 'v') ? $_GET : ApplicationHelper::getDefaultNavigationPage();

// Формирование навигационного массива для view
$navigationBlocksTV = [];

foreach($userNavigation as $block){
    
    $tmp_block = [];
    $tmp_block['label'] = $block['label'];
    
    foreach($block['views'] as $view){
        
        $tmp_section['label'] = $view['label'];
        $tmp_section['ref'] = "/home/navigation?b={$block['name']}&v={$view['name']}";
        $tmp_section['counter'] = $view['show_counter'] ? $view['class_name']::getCount(Session::getUserId()) : false;
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
        $tmp_section['is_selected'] = ($ref['value'] == '/'._URN_) ? true : false;
        $tmp_block['sections'][] = $tmp_section;
    }
    
    $navigationBlocksTV[] = $tmp_block;
}

//var_dump($navigationBlocksTV);

$variablesTV->setValue('navigationBlocks', $navigationBlocksTV);

// В случае НЕ навигационной странцы, view необходимо подключать через routes
if($isNavigationPage){
    // Проверка на существование view файла была выполнена ранее
    require_once _ROOT_.'/views/home/navigation/navigation_sidebar.php';
}
