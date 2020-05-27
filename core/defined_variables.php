<?php

// Блок объявления констант, связанных с путями в ФС сервера
define('_ROOT_', $_SERVER['DOCUMENT_ROOT']);                    // Корневая директория веб-проекта
define('_APPLICATIONS_FILES_', '/var/www/applications_files');  // Директория файлов заявлений


// ---------------------------------------------------------------
// Блок объявления констант, связанных с бизнес-логикой приложения
// ---------------------------------------------------------------


// Обозначения ролей в соответствии с `code_role`.`system_value`
define('_ROLES', ['APP'     => 'APP',     // Заявитель
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


// Обозначения ролей в заявлении
define('_ROLE_IN_APPLICATION', ['AUTHOR' => 'AUTHOR' // Автор заявления

                               ]);

// Обозначение полей в заявлении
define('_PROPERTY_IN_APPLICATION', ['application_id'         => 'application_id',        // id заявления
                                    'expertise_purpose'      => 'expertise_purpose',     // Цель экспертизы
                                    'expertise_subject'      => 'expertise_subject',     // Предмет экспертизы
                                    'additional_information' => 'additional_information' // Дополнительная информация

                                   ]);



// Маппинг кодов и названий классов
// Первый уровень вложенности соответствует типу таблиц:
//    1 - Тип таблицы:   id   id_application   file_name   hash   is_uploaded
//    используется для НЕ структурных типов хранения
//    2 - Тип таблицы:   id   id_application   id_structure_node   file_name   hash   is_uploaded
//    используется для структурных типов хранения (документации)
//
// Второй уровень вложенности соответствует названию класса для этой таблицы
define('_FILES_TABLE_MAPPING', ['1' => ['1' => 'file_grbsTable'

                                       ],
                                '2' => ['1' => 'file_documentation1Table',
                                        '2' => ''
                                       ]
                               ]);