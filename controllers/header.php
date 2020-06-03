<?php

define('ROOT_CSS', '/views/css/');
define('ROOT_LIB_CSS', ROOT_CSS.'lib/');
define('TMP_CSS', ROOT_CSS.'tmp/');

define('ROOT_JS', '/views/js/');
define('ROOT_LIB_JS', ROOT_JS.'lib/');
define('TMP_JS', ROOT_JS.'tmp/');


// Подключаемые файлы
$sourcesFiles = [];
// Название страницы
$pageName = '';

switch(_URNNAME_){

    case '':
        $pageName = 'АИС';
        $sourcesFiles = [...CreateSource('css', ROOT_CSS, 'entry.css'),
                         ...GetFontAwesome4Case(),
                         ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                         ...CreateSource('js', ROOT_JS, 'API_login.js')
                        ];
        break;

    case 'home/create_application' :
        $pageName = 'АИС';
        $sourcesFiles = [...CreateSource('css', ROOT_CSS, 'entry.css'),
                         ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'modal.css', 'calendar.css'),
                         ...CreateSource('css', TMP_CSS, 'create_application.css'),
                         ...GetFontAwesome5Case(),
                         ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                         ...CreateSource('js', TMP_JS, 'sidebar.js', 'section.js', 'validation.js', 'cards.js',
                            'modal.js', 'calendar.js', 'radio.js', 'save_application.js', 'test.js')
                        ];
        break;
   
    case 'home/application/view' :
       $pageName = 'АИС';
       $sourcesFiles = [...CreateSource('css', ROOT_CSS, 'entry.css'),
          ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css',
           'footer.css', 'modal.css', 'calendar.css'),
          ...CreateSource('css', TMP_CSS, 'create_application.css', 'view_application.css'),
          ...GetFontAwesome5Case(),
          ...CreateSource('js', TMP_JS, 'sidebar.js', 'section.js', 'validation.js', 'cards.js',
             'modal.js', 'calendar.js', 'radio.js', 'save_application.js', 'test.js')
       ];
       break;

    case 'tmp/form_to_file_download' :
        $pageName = 'Загрузка файлов на сервер';
        $sourcesFiles = [...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                         ...CreateSource('js', TMP_JS, 'file_download.js')
                        ];
        break;
}


// Предназначен для создания html ссылки на ресурс
// Принимает параметры-----------------------------------
// type  string : css / js
// path  string : пусть к файлу в ФС сервера, либо '', если ссылка на внешний ресурс
// names string : перечисления названий файлов
// Возвращает параметры-----------------------------------
// array : ссылки на ресурсы
//
function CreateSource(string $type, string $path, string ...$names):array {

    $sources = [
        'css' => ['<link rel="stylesheet" href="','">'],
        'js'  => ['<script src="','"></script>']
    ];

    $str = $sources[$type];

    $arr = [];
    foreach ($names as $name){

        $arr[] = $str[0].$path.$name.$str[1];
    }
    return $arr;
}

//---------------------- Готовые кейсы ----------------------

function GetFontAwesome4Case():array {
    return CreateSource('css', ROOT_LIB_CSS.'font-awesome-4.7.0/css/', 'font-awesome.min.css');
}

function GetFontAwesome5Case():array {
    return CreateSource('css', ROOT_LIB_CSS.'fontawesome-free-5.13.0-web/css/', 'all.min.css');
}