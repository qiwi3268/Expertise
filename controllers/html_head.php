<?php

define('ROOT_CSS', '/views/css/');
define('ROOT_LIB_CSS', ROOT_CSS . 'lib/');
define('TMP_CSS', ROOT_CSS . 'tmp/');

define('ROOT_JS', '/views/js/');
define('APPLICATION_JS', '/views/js/application/');
define('MODALS_JS', '/views/js/modals/');
define('ROOT_LIB_JS', ROOT_JS . 'lib/');
define('TMP_JS', ROOT_JS . 'tmp/');


// Подключаемые файлыs
$sourcesFiles = [];
// Название страницы
$pageName = '';

switch (URN) {

    case '' :
        $pageName = 'АИС';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'modal.css'),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('js', ROOT_JS, 'ErrorHandler.js', 'BrowserHelper.js', 'API_login.js'),
            ...CreateSource('js', MODALS_JS, 'ErrorModal.js'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),

        ];
        break;

    case 'home/application/create' :
        $pageName = 'АИС_create';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'header.css', 'footer.css', 'main.css', 'modal.css', 'radio.css', 'calendar.css', 'file_modal.css', 'files.css', 'documentation.css', 'multiple_block.css', 'sign_modal.css'),
            ...CreateSource('css', TMP_CSS, 'create_application.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js', 'cadesplugin_api.js'),
            ...CreateSource('js', ROOT_JS, 'ErrorHandler.js', 'main.js', 'API.js', 'section.js', 'validation.js', 'DependenciesHandler.js', 'radio.js', 'PerfectCades.js', 'BrowserHelper.js', 'FileChecker.js', 'FileNeeds.js', 'MultipleBlock.js', 'GeFile.js', 'PartBlock.js'),
            ...CreateSource('js', MODALS_JS, 'Calendar.js', 'ErrorModal.js', 'FileUploader.js', 'Misc.js', 'SignView.js', 'SignHandler.js'),
            ...CreateSource('js', APPLICATION_JS, 'create.js', 'save.js', 'sidebar.js'),
        ];
        break;

    case 'home/expertise_cards/application/view' :
        $pageName = 'АИС_view';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'action_sidebar.css', 'radio.css', 'sign_modal.css', 'tooltip.css'),
            ...CreateSource('css', TMP_CSS, 'create_application.css', 'view_application.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', ROOT_JS, 'section.js', 'main.js', 'API.js', 'GeFile.js', 'ErrorHandler.js'),
            ...CreateSource('js', MODALS_JS, 'ErrorModal.js', 'SignView.js', 'Tooltip.js'),
        ];
        break;

    case 'home/application/edit' :
        $pageName = 'АИС';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'modal.css', 'calendar.css'),
            ...CreateSource('css', TMP_CSS, 'create_application.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', TMP_JS, 'sidebar.js', 'section.js', 'validation.js', 'cards.js', 'calendar.js', 'radio.js', 'save_application.js', 'test.js')
        ];
        break;

    case 'home/expertise_cards/total_cc/view' :
        $pageName = 'АИС_view';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'create_application.css', 'view_application.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'action_sidebar.css', 'radio.css', 'sign_modal.css', 'tooltip.css', 'comments_table.css', 'total_cc_view.css'),
            ...CreateSource('js', ROOT_JS, 'main.js', 'section.js', 'DependenciesHandler.js'),
            ...CreateSource('js', MODALS_JS, 'Tooltip.js', 'ErrorModal.js'),
        ];
        break;


    case 'home/expertise_cards/section_documentation_1/view' :
        $pageName = 'АИС_view';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'create_application.css', 'view_application.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'sign.css', 'action_sidebar.css', 'tooltip.css', 'comments_table.css', 'section_view.css', 'statistic.css'),
            ...CreateSource('js', ROOT_JS, 'main.js', 'section.js', 'GeFile.js'),
            ...CreateSource('js', MODALS_JS, 'Tooltip.js', 'ErrorModal.js', 'SignView.js'),
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
            ...CreateSource('js', ROOT_JS, 'sorting.js')
        ];
        break;

    case 'home/expertise_cards/application/actions/action_2' :
        $pageName = 'Action_2';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css'),
            ...CreateSource('css', TMP_CSS, 'files.css', 'sign_modal.css', 'modal.css','action_2.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', APPLICATION_JS . 'actions/', 'action_2.js'),
            ...CreateSource('js', ROOT_JS, 'main.js', 'API.js', 'ErrorHandler.js', 'GeFile.js', 'Drag&Drop.js' , 'section.js'),
            ...CreateSource('js', MODALS_JS,'SignView.js', 'Misc.js', 'ErrorModal.js')
        ];
        break;

    case 'home/test_1' :
        $pageName = 'Тест';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css'),
            ...CreateSource('css', TMP_CSS, 'action_sidebar.css', 'test_1.css', 'radio.css', 'files.css', 'comment_groups.css', 'comment_modal.css', 'group_modal.css','action_header.css', 'comments_table.css'),
            ...CreateSource('js', ROOT_JS, 'ErrorHandler.js'),
            ...CreateSource('js', MODALS_JS,'ErrorModal.js', 'CommentGroup.js'),
            ...CreateSource('js', ROOT_JS . 'expertise_cards/documents/total_cc/actions/', 'action_2.js'),
        ];
        break;

    case 'home/expertise_cards/section_documentation_1/actions/action_1' :
        $pageName = 'Раздел';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...GetTinyMCECase(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css'),
            ...CreateSource('css', TMP_CSS,'radio.css', 'files.css', 'documentation.css', 'multiple_block.css', 'modal.css', 'sign.css', 'tooltip.css', 'create_application.css', 'comment_modal.css', 'comments_table.css','section_create_test.css'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', ROOT_JS, 'section.js', 'main.js', 'API.js', 'DependenciesHandler.js', 'MultipleBlock.js', 'PartBlock.js', 'radio.js', 'GeFile.js', 'validation.js', 'CommentsTable.js', 'GeComment.js'),
            ...CreateSource('js', MODALS_JS,'ErrorModal.js', 'CommentCreator.js', 'SignView.js', 'Misc.js', 'Tooltip.js'),
            ...CreateSource('js', ROOT_JS . 'expertise_cards/documents/section/actions/', 'action_1.js'),

        ];
        break;

    case 'home/expertise_cards/total_cc/actions/action_1' :
        $pageName = 'Общая часть';
        $sourcesFiles = [...GetFontAwesome5Case(),
            ...CreateSource('css', ROOT_CSS, 'entry.css'),
            ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css'),
            ...CreateSource('css', TMP_CSS,'multiple_block.css', 'create_application.css', 'action_3.css', 'modal.css', 'radio.css'),
            ...CreateSource('js', ROOT_JS, 'main.js', 'API.js', 'section.js', 'DependenciesHandler.js', 'radio.js', 'MultipleBlock.js', 'PartBlock.js', 'validation.js'),
            ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
            ...CreateSource('js', MODALS_JS, 'ErrorModal.js', 'SignView.js', 'Misc.js'),
            ...CreateSource('js', ROOT_JS . 'total_cc/actions/', 'action_1.js'),
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

function GetTinyMCECase(): array
{
    return CreateSource('js', ROOT_LIB_JS . 'tinymce/js/tinymce/', 'tinymce.min.js');
}
