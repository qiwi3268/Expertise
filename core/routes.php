<?php


return [

    '' => [
        'Classes'        => ['VariableTransfer'],
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
    'home/API_file_uploader' => [
        '/Classes/'         => ['FilesUpload', 'FilesTableMapping'],
        'API'               => ['file_uploader']
    ],
    'home/API_file_checker' => [
        '/Classes/'         => ['FilesTableMapping'],
        'API'               => ['file_checker']
    ],
    'home/API_file_needs_setter' => [
        '/Classes/'         => ['FilesTableMapping'],
        'API'               => ['file_needs_setter']
    ],

    //-------------------------------


    'test' => [
        'ROOTcontrollers' => ['test']
    ],


    'home/application/API_save_form' => [
        'access'                     => [],
        '/Classes/ApplicationForm/'  => ['SaveHandler'],
        'API'                        => ['save_form']
    ],

    'home/API_save_files' => [
        'access'                      => [],
        'API'                         => ['save_files']
    ],



    //todo перенести view create_application в ветку /home/application/
    //todo привести display_dependencies_application_form и create_application_dependencies к единообразному названию
    'home/application/create' => [
        'access'                    => [],
        'ROOTClasses'               => ['VariableTransfer', 'NodeStructure'],
        'ROOTcontrollers'           => ['header'],
        'ROOTviews'                 => ['header'],
        '/Classes/ApplicationForm/' => ['MiscInitialization'],
        'controllers'               => ['create_application',
                                        'display_dependencies_application_form'],
        '/controllers/home/'        => ['main_header'],
        '/views/home/'              => ['create_application_dependencies', 'main_header', 'create_application', 'main_footer'],
    ],


    'home/application/view' => [
        'access'              => [],
        'ROOTClasses'         => ['VariableTransfer', 'FilesTableMapping', 'NodeStructure', 'FilesInitialization'],
        'ROOTcontrollers'     => ['header'],
        'ABScontrollers'      => ['/controllers/home/main_header.php'],
        'controllers'         => ['action_sidebar',
                                  'view_application',
                                  'validation_block_application_form'],
        'ROOTviews'           => ['header'],
        '/views/home/%header' => ['main_header'],
        'views'               => ['hierarchy_sidebar', 'view_application', 'action_sidebar'],
        '/views/home/%footer' => ['main_footer'],
    ],

    'home/application/edit'  => [
        'access'             => [],
        'ROOTClasses'          => ['VariableTransfer'],
        'ROOTcontrollers'    => ['header'],
        'ROOTviews'          => ['header'],
        '/Classes/ApplicationForm/'   => ['MiscInitialization', 'MiscInitializationEditForm'],
        'controllers'        => [
                                 'edit_application',
                                 'validation_block_application_form',
                                 'display_dependencies_application_form'],
        '/controllers/home/' => ['main_header'],
        '/views/home/'       => ['create_application_dependencies', 'main_header', 'create_application', 'main_footer']
    ],


    'home/navigation' => [
        'ROOTClasses' => ['VariableTransfer', 'Navigation'],
        'ROOTcontrollers'    => ['header'],
        'ROOTviews'          => ['header'],
        'controllers' => ['navigation'],
        '/views/home/%footer' => ['main_footer'],
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
        'ROOTClasses'     => ['NodeStructure', 'VariableTransfer'],
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