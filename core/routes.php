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
        'ROOTClasses'     => ['FilesUnload'],
        'controllers'     => ['file_unloader']
    ],

    'API_file_uploader' => [
        'Classes'         => ['FilesUpload', 'FilesTableMapping'],
        'API'             => ['file_uploader']
    ],

    'API_file_checker' => [
        'Classes'         => ['FilesTableMapping'],
        'API'             => ['file_checker']
    ],

    'test' => [
        'ROOTcontrollers' => ['test']
    ],
    
    //todo перенести в ветку /home/application/
    'home/API_save_application' => [
        'access'                       => [],
        '/Classes/ApplicationForm/'    => ['SaveHandler'],
        'API'                          => ['save_application']
    ],
    
    //todo перенести view create_application в ветку /home/application/
    'home/application/create' => [
        'access'               => [],
        'ROOTcontrollers'      => ['header'],
        'ROOTviews'            => ['header'],
        'ROOTClasses'          => ['VariableTransfer'],
        '/Classes/ApplicationForm/'   => ['MiscInitialization'],
        'controllers'          => ['create_application',
                                   'initialization_misc_application_form',
                                   'display_dependencies_application_form'],
        '/controllers/home/'   => ['main_header'],
        '/views/home/'         => ['create_application_dependencies', 'main_header', 'create_application', 'main_footer'],
    ],

    
    'home/application/view' => [
        'access'              => [],
        'ROOTcontrollers'     => ['header'],
        'ROOTClasses'         => ['VariableTransfer'],
        'ABScontrollers'      => ['/controllers/home/main_header.php'],
        'controllers'         => ['view_application',
                                  'action_sidebar',
                                  'validation_block_application_form'],
        'ROOTviews'           => ['header'],
        '/views/home/%header' => ['main_header'],
        'views'               => ['hierarchy_sidebar', 'view_application', 'action_sidebar'],
        '/views/home/%footer' => ['main_footer'],
    ],
    
    'home/application/edit'  => [
        'access'             => [],
        'ROOTcontrollers'    => ['header'],
        'ROOTviews'          => ['header'],
        'ROOTClasses'        => ['VariableTransfer'],
        'controllers'        => ['initialization_misc_application_form',
                                 'edit_application',
                                 'validation_block_application_form',
                                 'display_dependencies_application_form'],
        '/controllers/home/' => ['main_header'],
        '/views/home/'       => ['create_application_dependencies', 'main_header', 'create_application', 'main_footer']
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
        'controllers'     => ['documentation_structure'],
        'views'           => ['documentation_structure']
    ]

];

//todo должны начинаться и заканчиваться на /

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