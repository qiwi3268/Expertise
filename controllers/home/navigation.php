<?php


// Данная страница представляет собой мини-движок по формированию навигационных страниц
// Подключение sidebar'а и view происходит напрямую, поскольку заранее неизвестно, какая view нужна
<<<<<<< HEAD
$variablesTV = VariableTransfer::getInstance();

// Получение параметров навигационной страницы
list('b' => $G_block, 'v' => $G_view) = checkParamsGET('b', 'v') ? $_GET : ApplicationHelper::getDefaultNavigationPage();
$G_page = checkParamsGET('page') ? clearHtmlArr($_GET)['page'] : 1;
=======

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

>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb

$Navigation = new Navigation(Session::getUserRoles());
$userNavigation = $Navigation->getUserNavigation();

<<<<<<< HEAD
=======
//var_dump($userNavigation);
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb

// Проверка на то, что указанная в GET-параметрах навигационная страница существует в навигации пользователя
$blockIsset = false;
$viewIsset = false;

<<<<<<< HEAD

foreach($userNavigation as $block){
    
    // Находим выбранной блок
=======
foreach($userNavigation as $block){
    
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
    if($block['name'] != $G_block) continue;
    
    $blockIsset = true;
    
<<<<<<< HEAD
    if(isset($block['views'])){
        
        foreach($block['views'] as $view){
            
            // В выбранном блоке находим выбранное view
            if($view['name'] != $G_view) continue;
        
            $viewIsset = true;
            break 2;
        }
=======
    foreach($block['views'] as $view){
        
        if($view['name'] != $G_view) continue;
        
        $viewIsset = true;
        break 2;
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
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


<<<<<<< HEAD
$path_sidebar_controller = _ROOT_.'/controllers/home/navigation_sidebar.php';
$path_sidebar_view = _ROOT_.'/views/home/navigation/navigation_sidebar.php';

if(!file_exists($path_sidebar_controller)){
    throw new Exception("Отсутствует controller navigation_sidebar по пути: '{$path_sidebar_controller}'");
}
if(!file_exists($path_sidebar_view)){
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


// Получеие сортировки и пагинации ---------------------------------------------------------
//
$userId = Session::getUserId();

$NavigationParameters = new NavigationParameters($viewName);
// Количество отображаемых элементов на странице
$dataPerPage = $NavigationParameters->getDataPerPage();

$Pagination = new Pagination($className::getCountByIdUser($userId), $dataPerPage, $G_page);

// Флаги существования предыдущей/следующей страницы
$issetPreviousPage = $Pagination->checkIssetPreviousPage();
$issetNextPage = $Pagination->checkIssetNextPage();
$variablesTV->setExistenceFlag('pagination_PreviousPage', $issetPreviousPage);
$variablesTV->setExistenceFlag('pagination_NextPage', $issetNextPage);

// Ссылки на предыдущую / следующую страницу
$currentPage = $Pagination->getCurrentPage();

if($issetPreviousPage)$variablesTV->setValue('pagination_PreviousPageRef', '/'._URN_."?b={$G_block}&v={$G_view}&page=".($currentPage - 1));
if($issetNextPage)    $variablesTV->setValue('pagination_NextPageRef', '/'._URN_."?b={$G_block}&v={$G_view}&page=".($currentPage + 1));
// Надпись текущая страницы / все страницы
$variablesTV->setValue('pagination_CurrentPage', "{$currentPage} из {$Pagination->getPageCount()}");


// Запрос в БД c учётом сортировки и пагинации ---------------------------------------------
//
$SORT_name = $NavigationParameters->getSortName();
$SORT_type = $NavigationParameters->getSortType();
$LIMIT_offset = ($currentPage - 1) * $dataPerPage;
$LIMIT_row_count = $dataPerPage;

$variablesTV->setValue('navigationData', $className::getAssocByIdUser($userId, $SORT_name, $SORT_type, $LIMIT_offset, $LIMIT_row_count));


// Передаем во view количество отображаемых элементов и сортировку -------------------------
//
// Количество отображаемых элементов (статические данные)
$navigationDataPerPageTV = [['description'   => 25,
                             'data_per_page' => 25,
                             'is_selected'   => $dataPerPage == 25],
                            ['description'   => 50,
                             'data_per_page' => 50,
                             'is_selected'   => $dataPerPage == 50],
                            ['description'   => 75,
                             'data_per_page' => 75,
                             'is_selected'   => $dataPerPage == 75]
];
$variablesTV->setValue('navigationDataPerPage', $navigationDataPerPageTV);

// Сортировка (динамические данные)
$navigationSortingTV = [];

foreach(_NAVIGATION_SORTING[$viewName] as $category){
    
    $tmp = [];
    $tmp['description'] = $category['description'];
    $tmp['sort_name'] = $category['column_name'];
    
    if($category['column_name'] == $SORT_name){
        $tmp['is_selected'] = true;
        $tmp['sort_type'] = $SORT_type;
    }else{
        $tmp['is_selected'] = false;
    }
    $navigationSortingTV[] = $tmp;
}
$variablesTV->setValue('navigationSorting', $navigationSortingTV);

$variablesTV->setValue('viewName', $viewName);

// Подключение view
require_once _ROOT_."/views/home/navigation/{$viewName}.php";
=======
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

>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
