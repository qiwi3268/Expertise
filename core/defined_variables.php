<?php

// Блок объявления констант, связанных с путями в ФС сервера
define('_ROOT_', '/var/www/html');                              // Корневая директория веб-проекта
define('_APPLICATIONS_FILES_', '/var/www/applications_files');  // Директория файлов заявлений
define('_TMP_BASE64_FILES_', '/var/www/hash/tmp_base64');       // Директория хранения временных файлов base64
define('_TMP_HASH_FILES_', '/var/www/hash/tmp_hash');       // Директория хранения временных файлов полученных hash'ей

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

// Маппинг соответствия файловой таблицы и соответствующей к ней таблицы подписей
// Ключ - существующий файловый класс. Значение - название класса
//
define('_SIGN_TABLE_MAPPING', ['file_grbsTable' => 'sign_grbsTable']);




// Наименование столбцов в таблице БД, по которым можно сортировать указанное view
// *** Данный константный массив валидируется в классе Navigation
// Ключ - название view (view_name из XML-схемы)
// Значение - массив, где:
//     Ключ = column_name !!!
//     Значение - массив с двумя параметрами, где:
//         description - описание кнопки на панели сортировки
//         column_name - название столбца в БД для сортировки
// -----------------------------------------------------------------------------------------
// view_1 - таблица applications
//
define('_NAVIGATION_SORTING', ['view_1' => ['id'             => ['description' => 'По id',
                                                                 'column_name' => 'id'],
                                            'numerical_name' => ['description' => 'По наименованию',
                                                                 'column_name' => 'numerical_name']
                                           ]
]);


// Существующие алгоритмы подписи
define('sign_algorithms', ['1.2.643.2.2.19'    => '1.2.643.2.2.19',    // Алгоритм ГОСТ Р 34.10-2001, используемый при экспорте/импорте ключей
                           '1.2.643.7.1.1.1.1' => '1.2.643.7.1.1.1.1', // Алгоритм ГОСТ Р 34.10-2012 для ключей длины 256 бит, используемый при экспорте/импорте ключей
                           '1.2.643.7.1.1.1.2' => '1.2.643.7.1.1.1.2', // Алгоритм ГОСТ Р 34.10-2012 для ключей длины 512 бит, используемый при экспорте/импорте ключей
]);

// Существующие алгоритмы хэширования
// Соответсвие алгоритмов хэширования к алгоритмам подписи
define('hash_algorithms', [sign_algorithms['1.2.643.2.2.19']    => '1.2.643.2.2.9',     // Функция хэширования ГОСТ Р 34.11-94
                           sign_algorithms['1.2.643.7.1.1.1.1'] => '1.2.643.7.1.1.2.2', // Функция хэширования ГОСТ Р 34.11-2012, длина выхода 256 бит
                           sign_algorithms['1.2.643.7.1.1.1.2'] => '1.2.643.7.1.1.2.3'  // Функция хэширования ГОСТ Р 34.11-2012, длина выхода 512 бит
]);




