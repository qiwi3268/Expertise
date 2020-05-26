<?php


return [

    '' => [
        'controllers' 	 => ['header', 'entry'],
        'views'          => ['header', 'entry']
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
        'ROOTClasses'     => ['PHPSpreadSheetAddon']
    ],



    'tmp/create_application' => [
        'access'          => [],
        'ROOTcontrollers' => ['header'],
        'ROOTviews'       => ['header'],
        'controllers'     => ['create_application'],
        'views'           => ['create_application']
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


//redirect      	Необязтательный парметр	СТРОКА
//					Переадресует страницу на указанный роут,
//					игнорируя все остальные нижепрописанные настройки

//access      		Необязтательный парметр	СТРОКОВЫЙ МАССИВ
//					Вызывает поочереди функции проверки доступа,
//					указанные в элементах массива.
//					Функции хранятся в файле /core/access

//*** С точки зрения программной реализации:
//Если в пути есть вхождение подстроки 'ABS', то берется именно абсолютный путь из значения массива,
//т.е. абсолютно без разницы как называется путь, если в ней есть подстрока 'ABS'

//Если в пути есть вхождение подстроки 'ROOT', то 'ROOT' вырезается и поиск файлов ведется в
//корневой директории получившегося названия

//Если нет ни 'ABS', ни 'ROOT', то ищутся файлы согласно файловой иеррхии движка