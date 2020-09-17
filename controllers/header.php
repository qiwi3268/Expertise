<?php

define('ROOT_CSS', '/views/css/');
define('ROOT_LIB_CSS', ROOT_CSS . 'lib/');
define('TMP_CSS', ROOT_CSS . 'tmp/');

define('ROOT_JS', '/views/js/');
define('ROOT_LIB_JS', ROOT_JS . 'lib/');
define('TMP_JS', ROOT_JS . 'tmp/');


// Подключаемые файлы
$sourcesFiles = [];
// Название страницы
$pageName = '';

switch (URN) {

    case '' :
        $pageName = 'АИС';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'modal.css'),
            ...CreateSource('js', TMP_JS, 'ErrorHandler.js', 'BrowserHelper.js'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', ROOT_JS, 'API_login.js'),

        ];
        break;

    case 'home/application/create' :
        $pageName = 'АИС_create';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'header.css', 'footer.css', 'main.css', 'modal.css', 'radio.css', 'calendar.css', 'file_modal.css', 'files.css', 'documentation.css', 'multiple_block.css', 'sign_modal.css'),
            ...CreateSource('css', TMP_CSS, 'create_application.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js', 'cadesplugin_api.js'),
            ...CreateSource('js', TMP_JS, 'ErrorHandler.js', 'main.js', 'API.js', 'modal.js', 'Misc.js', 'sidebar.js', 'section.js', 'validation.js', 'DependenciesHandler.js', 'Calendar.js', 'radio.js', 'checkbox.js', 'save_application.js', 'SignView.js', 'SignHandler.js', 'PerfectCades.js', 'BrowserHelper.js', 'FileChecker.js', 'file_needs.js', 'FileUploader.js', 'MultipleBlock.js', 'GeFile.js', 'PartBlock.js')
        ];
        break;

    case 'home/application/view' :
        $pageName = 'АИС_view';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'action_sidebar.css', 'radio.css', 'sign_modal.css'),
            ...CreateSource('css', TMP_CSS, 'create_application.css', 'view_application.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js', ),
            ...CreateSource('js', TMP_JS, 'section.js', 'main.js', 'API.js', 'GeFile.js', 'SignView.js', 'SignHandler.js', 'ErrorHandler.js')
        ];
        break;

    case 'home/application/edit' :
        $pageName = 'АИС';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'modal.css', 'calendar.css'),
            ...CreateSource('css', TMP_CSS, 'create_application.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', TMP_JS, 'sidebar.js', 'section.js', 'validation.js', 'cards.js',
                'modal.js', 'calendar.js', 'radio.js', 'checkbox.js', 'save_application.js', 'test.js')
        ];
        break;

    case 'tmp/form_to_file_download' :
        $pageName = 'Загрузка файлов на сервер';
        $sourcesFiles = [...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', TMP_JS, 'file_download.js')
        ];
        break;

    case 'tmp/documentation_structure' :
        $pageName = 'Документация';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'files.css'),
            ...CreateSource('css', TMP_CSS, 'documentation.css')
        ];
        break;

    case 'home/navigation' :
        $pageName = 'Навигация';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css'),
            ...CreateSource('css', TMP_CSS, 'navigation.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', TMP_JS, 'sorting.js')
        ];
        break;

    case 'home/application/actions/action_2' :
        $pageName = 'Action_2';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css'),
            ...CreateSource('css', TMP_CSS, 'files.css', 'sign_modal.css', 'action_2.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', TMP_JS, 'main.js', 'API.js', 'ErrorHandler.js', 'GeFile.js', 'SignView.js', 'Drag&Drop.js', 'action_2.js', 'section.js')
        ];
        break;
}

$variablesTV = \Lib\Singles\VariableTransfer::getInstance();
$variablesTV->setValue('pageName', $pageName);
$variablesTV->setValue('sourcesFiles', $sourcesFiles);


// Предназначен для создания html ссылки на ресурс
// Принимает параметры-----------------------------------
// type  string : css / js
// path  string : пусть к файлу в ФС сервера, либо '', если ссылка на внешний ресурс
// names string : перечисления названий файлов
// Возвращает параметры-----------------------------------
// array : ссылки на ресурсы
//
function CreateSource(string $type, string $path, string ...$names): array
{

    $sources = [
        'css' => ['<link rel="stylesheet" href="', '">'],
        'js' => ['<script src="', '"></script>']
    ];

    $str = $sources[$type];

    $arr = [];
    foreach ($names as $name) {

        $arr[] = $str[0] . $path . $name . $str[1];
    }
    return $arr;
}

//---------------------- Готовые кейсы ----------------------

function GetFontAwesome4Case(): array
{
    return CreateSource('css', ROOT_LIB_CSS . 'font-awesome-4.7.0/css/', 'font-awesome.min.css');
}

function GetFontAwesome5Case(): array
{
    return CreateSource('css', ROOT_LIB_CSS . 'fontawesome-free-5.13.0-web/css/', 'all.min.css');
}