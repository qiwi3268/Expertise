<?php


use core\Classes\Session;


use Lib\Singles\VariableTransfer;
use Lib\Singles\Pagination;
use Lib\Singles\Helpers\PageAddress as PageAddressHelper;
use Classes\Navigation\Navigation;
use Classes\Navigation\NavigationParameters;


// Данная страница представляет собой мини-движок по формированию навигационных страниц
// Подключение sidebar'а и view происходит напрямую, поскольку заранее неизвестно, какая view нужна
$variablesTV = VariableTransfer::getInstance();

// Получение параметров навигационной страницы
list('b' => $G_block, 'v' => $G_view) = checkParamsGET('b', 'v') ? $_GET : PageAddressHelper::getDefaultNavigationPage();
$G_page = checkParamsGET('page') ? clearHtmlArr($_GET)['page'] : 1;

$navigation = new Navigation(Session::getUserRoles());
$userNavigation = $navigation->getUserNavigation();


// Проверка на то, что указанная в GET-параметрах навигационная страница существует в навигации пользователя
$blockIsset = false;
$viewIsset = false;


foreach ($userNavigation as $block) {

    // Находим выбранной блок
    if ($block['name'] != $G_block) continue;

    $blockIsset = true;

    if (isset($block['views'])) {

        foreach ($block['views'] as $view) {

            // В выбранном блоке находим выбранное view
            if ($view['name'] != $G_view) continue;

            $viewIsset = true;
            break 2;
        }
    }
    break;
}

if (!$blockIsset || !$viewIsset) {
    if (!$blockIsset) {
        throw new Exception("Указанный навигационный block: '{$G_block}' не существует для данного пользователя");
    } else {
        throw new Exception("Указанная навигационная view: '{$G_view}' не существует для данного пользователя");
    }
}


$path_sidebar_controller = ROOT . '/controllers/home/navigation_sidebar.php';
$path_sidebar_view = Navigation::VIEWS_PATH . '/navigation_sidebar.php';

if (!file_exists($path_sidebar_controller)) {
    throw new Exception("Отсутствует controller navigation_sidebar по пути: '{$path_sidebar_controller}'");
}
if (!file_exists($path_sidebar_view)) {
    throw new Exception("Отсутствует view navigation_sidebar по пути: '{$path_sidebar_view}'");
}

// Передаем навигацию пользователя в navigation_sidebar
$variablesTV->setExistenceFlag('isNavigationPage', true);
$variablesTV->setValue('userNavigation', $userNavigation);

// Подключаем контроллер, который внутри себя подключает своё view
require_once $path_sidebar_controller;

// Получаем блок и view текущей страницы. NB NV - navigation block/name
$NB = array_filter($userNavigation, fn($block) => ($block['name'] == $G_block));
$NV = array_filter(array_shift($NB)['views'], fn($view) => ($view['name'] == $G_view));

list('class_name' => $className, 'view_name' => $viewName) = array_shift($NV);
$className = Navigation::NAMESPACE_CLASSES . "\\{$className}";

// Получение сортировки и пагинации --------------------------------------------------------
//
$userId = Session::getUserId();

$navigationParameters = new NavigationParameters($viewName);
// Количество отображаемых элементов на странице
$dataPerPage = $navigationParameters->getDataPerPage();

$pagination = new Pagination($className::getCountByIdUser($userId), $dataPerPage, $G_page);

// Флаги существования предыдущей/следующей страницы
$issetPreviousPage = $pagination->checkIssetPreviousPage();
$issetNextPage = $pagination->checkIssetNextPage();

$variablesTV->setExistenceFlag('pagination_PreviousPage', $issetPreviousPage);
$variablesTV->setExistenceFlag('pagination_NextPage', $issetNextPage);

// Ссылки на предыдущую / следующую страницу
$currentPage = $pagination->getCurrentPage();

if ($issetPreviousPage) $variablesTV->setValue('pagination_PreviousPageRef', '/' . URN . "?b={$G_block}&v={$G_view}&page=" . ($currentPage - 1));
if ($issetNextPage) $variablesTV->setValue('pagination_NextPageRef', '/' . URN . "?b={$G_block}&v={$G_view}&page=" . ($currentPage + 1));
// Надпись текущая страницы / все страницы
$variablesTV->setValue('pagination_CurrentPage', "{$currentPage} из {$pagination->getPageCount()}");


// Запрос в БД c учётом сортировки и пагинации ---------------------------------------------
//

$SORT_name = $navigationParameters->getSortName();
$SORT_type = $navigationParameters->getSortType();

    //todo среднее переделать эту логику и если нет данных, то не отображать пагинацию и сделать через setExistanceflag в трансфере
if ($pagination->getPageCount() > 0) {

    $LIMIT_offset = ($currentPage - 1) * $dataPerPage;
    $LIMIT_row_count = $dataPerPage;

    $variablesTV->setValue('navigationData', $className::getAssocByIdUser($userId, $SORT_name, $SORT_type, $LIMIT_offset, $LIMIT_row_count));
} else {
    $variablesTV->setValue('navigationData', []);
}



// Передаем во view количество отображаемых элементов и сортировку -------------------------
//
// Количество отображаемых элементов (статические данные)
$navigationDataPerPageTV = [
    [
        'description'   => 25,
        'data_per_page' => 25,
        'is_selected'   => $dataPerPage == 25
    ],
    [
        'description'   => 50,
        'data_per_page' => 50,
        'is_selected'   => $dataPerPage == 50
    ],
    [
        'description'   => 75,
        'data_per_page' => 75,
        'is_selected'   => $dataPerPage == 75
    ]
];
$variablesTV->setValue('navigationDataPerPage', $navigationDataPerPageTV);

// Сортировка (динамические данные)
$navigationSortingTV = [];

foreach (NAVIGATION_SORTING[$viewName] as $category) {

    $tmp = [];
    $tmp['description'] = $category['description'];
    $tmp['sort_name'] = $category['column_name'];

    if ($category['column_name'] == $SORT_name) {
        $tmp['is_selected'] = true;
        $tmp['sort_type'] = $SORT_type;
    } else {
        $tmp['is_selected'] = false;
    }
    $navigationSortingTV[] = $tmp;
}
$variablesTV->setValue('navigationSorting', $navigationSortingTV);

$variablesTV->setValue('viewName', $viewName);

// Подключение view
require_once Navigation::VIEWS_PATH . "/view_header.php";
require_once Navigation::VIEWS_PATH . "/{$viewName}.php";
require_once Navigation::VIEWS_PATH . "/view_footer.php";