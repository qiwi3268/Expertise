<?php


use core\Classes\Session;
use Lib\Singles\VariableTransfer;
use Lib\Singles\Helpers\PageAddress as PageAddressHelper;
use Classes\Navigation\Navigation;


$variablesTV = VariableTransfer::getInstance();

// Вызван ли данный файл из навигационной страницы
// (существуют страницы, в которых есть этот sidebar, но они не /home/navigation)
$isNavigationPage = $variablesTV->getExistenceFlag('isNavigationPage%S');

if ($isNavigationPage) {
    $userNavigation = $variablesTV->getValue('userNavigation');
} else {
    $Navigation = new Navigation(Session::getUserRoles());
    $userNavigation = $Navigation->getUserNavigation();
}

// Получение параметров навигационной страницы
list('b' => $G_block, 'v' => $G_view) = checkParamsGET('b', 'v') ? $_GET : PageAddressHelper::getDefaultNavigationPage();

// Формирование навигационного массива для view
$navigationBlocksTV = [];

foreach ($userNavigation as $block) {

    $tmp_block = [];
    $tmp_block['label'] = $block['label'];

    if (isset($block['views'])) {

        foreach ($block['views'] as $view) {

            $tmp_section['label'] = $view['label'];
            $tmp_section['ref'] = '/' . URN . "?b={$block['name']}&v={$view['name']}";
            $tmp_section['counter'] = $view['show_counter'] ? call_user_func([Navigation::NAMESPACE_CLASSES . "\\{$view['class_name']}", 'getCountByIdUser'], Session::getUserId()) : false;
            $tmp_section['is_selected'] = ($G_block == $block['name']) && ($G_view == $view['name']);
            $tmp_block['sections'][] = $tmp_section;
        }
    }

    if (isset($block['refs'])) {

        foreach ($block['refs'] as $ref) {

            $tmp_section['label'] = $ref['label'];
            $tmp_section['ref'] = $ref['value'];
            $tmp_section['counter'] = false;
            $tmp_section['is_selected'] = $ref['value'] == '/' . URN;
            $tmp_block['sections'][] = $tmp_section;
        }
    }

    $navigationBlocksTV[] = $tmp_block;
}

$variablesTV->setValue('navigationBlocks', $navigationBlocksTV);

// В случае НЕ навигационной странцы, view необходимо подключать через routes
if ($isNavigationPage) {

    // Проверка на существование view файла была выполнена ранее
    require_once Navigation::VIEWS_PATH . '/navigation_sidebar.php';
}
