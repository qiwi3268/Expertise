<?php

// Блок объявления констант, связанных с путями в ФС сервера
define('_ROOT_', '/var/www/html');                              // Корневая директория веб-проекта
define('_APPLICATIONS_FILES_', '/var/www/applications_files');  // Директория файлов заявлений
define('_NAVIGATION_SETTINGS', '/var/www/html/settings/navigation.xml');

// ---------------------------------------------------------------
// Блок объявления констант, связанных с бизнес-логикой приложения
// ---------------------------------------------------------------


// Обозначения ролей в соответствии с `code_role`.`system_value`
define('_ROLE', ['APP'     => 'APP',     // Заявитель
                 'ADM'     => 'ADM',     // Администратор
                 'EXP'     => 'EXP',     // Эксперт
                 'EMP_EXP' => 'EMP_EXP', // EMP_EXP
                 'EMP_EST' => 'EMP_EST', // Сотрудник сметного отдела
                 'EMP_PTO' => 'EMP_PTO', // Сотрудник производственно-технического отдела
                 'EMP_PKR' => 'EMP_PKR', // Сотрудник отдела правовой и кадровой работы
                 'EMP_BUH' => 'EMP_BUH', // Сотрудник бухгалтерии
                 'EMP_RKS' => 'EMP_RKS', // Сотрудник отдела развития, контроля, сопровождения
                 'ZAM'     => 'ZAM',     // Заместитель руководителя учреждения
                 'BOSS'    => 'BOSS',    // Руководитель учреждения
                 'REPORT'  => 'REPORT'   // Формирование отчетов
]);


// Обозначения типов документов
define('_DOCUMENT_TYPE', ['application' => 'application',    // Заявление
                          'contract'    => 'contract'        // Договор
]);


// Обозначения ролей в заявлении
define('_ROLE_IN_APPLICATION', ['AUTHOR' => 'AUTHOR' // Автор заявления

]);

//todo дописать изменную с учетом крона структуру таблиц
// Маппинг кодов и названий классов
// Первый уровень вложенности соответствует типу таблиц:
//    1 - Тип таблицы:   id   id_application   file_name   hash   is_uploaded
//    используется для НЕ структурных типов хранения
//    2 - Тип таблицы:   id   id_application   id_structure_node   file_name   hash   is_uploaded
//    используется для структурных типов хранения (документации)
//
// Второй уровень вложенности соответствует названию класса для этой таблицы
define('_FILE_TABLE_MAPPING', [1 => [1 => 'file_grbsTable',
                                    ],
                               2 => [1 => 'file_documentation1Table',
                                     2 => 'file_documentation2Table'
                                    ]
                               ]);




// Наименование столбцов в таблице БД, по которым можно сортировать указанное view
// *** Данный константный массив валидируется в классе Navigation
// Ключ - название view -> view_name из XML-схемы
// Значение - массив, где ключ=значение -> название столбца в таблице БД
//
// view_1 - столбцы таблицы applications
//
define('_NAVIGATION_SORTING', ['view_1' => ['id'             => ['description' => 'По id',
                                                                 'column_name' => 'id'],
                                            'numerical_name' => ['description' => 'По наименованию',
                                                                 'column_name' => 'numerical_name']
                                           ]
]);




