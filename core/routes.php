<?php


return [

    '' => [
        'controllers' 	 => ['header', 'entry'],
        'views'          => ['header', 'entry'],
    ],



    'home/admin' => [

    ],

    'API_login' => [
        'API'   => ['login']
    ],


    'home/file_unloader' => [
        'controllers'     => ['file_unloader']
    ],
    'home/API_file_uploader' => [
        'API'               => ['file_uploader']
    ],
    'home/API_file_checker' => [
        'API'               => ['file_checker']
    ],
    'home/API_file_needs_setter' => [
        'API'               => ['file_needs_setter']
    ],

    'home/API_get_file_hash' => [
        'API'                => ['get_file_hash']
    ],

    'home/API_external_signature_verifier' => [
        'API'                => ['external_signature_verifier']
    ],

    'home/API_internal_signature_verifier' => [
        'API'                => ['internal_signature_verifier']
    ],


    //-------------------------------


    'test' => [
        'controllers' => ['test']
    ],

    'files_in_documentation' => [
        'controllers' => ['files_in_documentation']
    ],


    'home/application/API_save_form' => [
        'API'                                 => ['save_form']
    ],

    'home/API_save_files' => [
        'API'                         => ['save_files']
    ],

    'home/API_navigation_cookie' => [
        'API'                         => ['navigation_cookie']
    ],

    'home/API_action_executor' => [
        'API' => ['action_executor']
    ],



    //todo перенести view create_application в ветку /home/application/
    //todo привести display_dependencies_application_form и create_application_dependencies к единообразному названию
    'home/application/create' => [
        'access'                                => [],
        'ROOTcontrollers'                       => ['header'],
        'ROOTviews'                             => ['header'],
        'controllers'                           => ['create_application', 'display_dependencies_application_form'],
        '/controllers/home/'                    => ['main_header'],
        '/views/home/'                          => ['create_application_dependencies', 'main_header', 'create_application'],
        '/views/home/modals/'                   => ['calendar', 'file_upload', 'create_sign', 'error'],
        '/views/home/%footer'                   => ['main_footer'],
    ],


    'home/application/view' => [
        'instance_callback'   => [
            [
                'class' => '\Classes\DocumentParameters\ExpertiseCard',
                'method' => 'defineDocumentParameters'
            ],
        ],
        'ROOTcontrollers'     => ['header'],
        '/controllers/home/'  => ['main_header'],
        'controllers'         => ['action_sidebar', 'view_application', 'validation_block_application_form'],
        'ROOTviews'           => ['header'],
        '/views/home/%header' => ['main_header'],
        'views'               => ['hierarchy_sidebar', 'view_application', 'action_sidebar'],
        '/views/home/modals/' => ['view_sign', 'error'],
        '/views/home/%footer' => ['main_footer'],
    ],

    //todo на эту мусорку не обращать внимание
    'home/application/edit'  => [
        'access'             => [],
        'ROOTClasses'        => [],
        'ROOTcontrollers'    => ['header'],
        'ROOTviews'          => ['header'],
        //'/Classes/ApplicationForm/'   => ['MiscInitialization', 'MiscInitializationEditForm'],
        'controllers'        => [
            'edit_application',
            'validation_block_application_form',
            'display_dependencies_application_form'],
        '/controllers/home/' => ['main_header'],
        '/views/home/'       => ['create_application_dependencies', 'main_header', 'create_application', 'main_footer']
    ],

    'home/application/actions/action_1' => [
        'instance_callback%1'       => [
            [
                'class' => '\Classes\DocumentParameters\ActionPage',
                'method' => 'defineDocumentParameters'
            ],
        ],
        'controllers' => ['action_1']
    ],

    'home/application/actions/action_2' => [
        'ROOTcontrollers'     => ['header'],
        'ROOTviews'           => ['header'],
        '/controllers/home/'  => ['main_header'],
        '/views/home/%header' => ['main_header'],
        'controllers'         => ['action_2'],
        'views'               => ['action_2'],
        '/views/home/modals/' => ['view_sign', 'error'],
        '/views/home/%footer' => ['main_footer'],
    ],


    'home/navigation' => [
        'ROOTcontrollers'         => ['header'],
        'ROOTviews'               => ['header'],
        '/controllers/home/'      => ['main_header'],
        'views'                   => ['main_header'],
        'controllers'             => ['navigation'],
        '/views/home/%footer'     => ['main_footer'],
        '/views/home/modals/'     => ['static_error'],

    ],

    'sign' => [
        '/views/tmp/'             => ['cades']
    ],





    'tmp/registration_user' => [
        'controllers'     => ['registration_user']
    ],

    'tmp/xml' => [
        'controllers'     => ['xml']
    ],

    'tmp/cades' => [
        'controllers'       => ['cades'],
        'views'             => ['cades']
    ],

    'tmp/mail' => [
        'ROOTClasses'     => ['PHPMailerAddon'],
        'controllers'     => ['mail']
    ],

    'tmp/form_to_file_download' => [
        'ROOTcontrollers' => ['header'],
        'ROOTviews'       => ['header'],
        'views'           => ['form_to_file_download']
    ],

    'tmp/documentation_structure' => [
        'ROOTcontrollers' => ['header'],
        'ROOTviews'       => ['header'],
        'controllers'     => ['documentation_structure'],
        'views'           => ['documentation_structure']
    ]

];


// redirect  	Необязтательный парметр	СТРОКА
//					Переадресует страницу на указанный роут,
//					игнорируя все остальные нижепрописанные настройки

// access      Необязтательный парметр	СТРОКОВЫЙ МАССИВ
//					Вызывает поочереди функции проверки доступа,
//					указанные в элементах массива.
//					Функции хранятся в файле /core/Classes/Access

// *** С точки зрения программной реализации:
// Если в пути есть вхождение подстроки 'ABS', то берется именно абсолютный путь из значения массива,
// т.е. абсолютно без разницы как называется путь, если в ней есть подстрока 'ABS'

// Если в пути есть вхождение подстроки 'ROOT', то 'ROOT' вырезается и поиск файлов ведется в
// корневой директории получившегося названия

// Если нет ни 'ABS', ни 'ROOT', то ищутся файлы согласно файловой иеррхии движка