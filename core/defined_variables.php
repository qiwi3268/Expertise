<?php

// Блок объявления констант, связанных с путями в ФС сервера
define('_ROOT_', $_SERVER['DOCUMENT_ROOT']);                    // Корневая директория веб-проекта
define('_APPLICATIONS_FILES_', '/var/www/applications_files');  // Директория файлов заявлений


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


// Обозначения ролей в заявлении
define('_ROLE_IN_APPLICATION', ['AUTHOR' => 'AUTHOR' // Автор заявления

                               ]);

// Имена полей в форме заявления
define('_PROPERTY_IN_APPLICATION', ['application_id'                         => 'application_id',                         // id заявления
                                    'expertise_purpose'                      => 'expertise_purpose',                      // Цель обращения
                                    'expertise_subjects'                     => 'expertise_subjects',                     // Предмет экспертизы
                                    'additional_information'                 => 'additional_information',                 // Дополнительная информация
                                    'object_name'                            => 'object_name',                            // Наименование объекта
                                    'type_of_object'                         => 'type_of_object',                         // Вид объекта
                                    'functional_purpose'                     => 'functional_purpose',                     // Функциональное назначение
                                    'functional_purpose_subsector'           => 'functional_purpose_subsector',           // Функциональное назначение. Подотрасль
                                    'functional_purpose_group'               => 'functional_purpose_group',               // Функциональное назначение. Группа
                                    'number_planning_documentation_approval' => 'number_planning_documentation_approval', // Номер утверждения документации по планировке территории
                                    'date_planning_documentation_approval'   => 'date_planning_documentation_approval',   // Дата утверждения документации по планировке территории
                                    'number_GPZU'                            => 'number_GPZU',                            // Номер ГПЗУ
                                    'date_GPZU'                              => 'date_GPZU',                              // Дата ГПЗУ
                                    'type_of_work'                           => 'type_of_work',                           // Вид работ
                                    'cadastral_number'                       => 'cadastral_number',                       // Кадастровый номер земельного участка
                                    'cultural_object_type_checkbox'          => 'cultural_object_type_checkbox',
                                    'cultural_object_type'                   => 'cultural_object_type',
                                   ]);

// Имена столбцов в таблице applications
define('_COLUMN_NAME_IN_APPLICATIONS_TABLE', ['id'                                     => 'id',                                     // id заявления
                                              'is_saved'                               => 'is_saved',                               // Флаг сохранения
                                              'id_author'                              => 'id_author',                              // id автора
                                              'numerical_name'                         => 'numerical_name',                         // Численное имя
                                              'id_expertise_purpose'                   => 'id_expertise_purpose',                   // id цели экспертизы
                                              'additional_information'                 => 'additional_information',                 // Дополнительная информация
                                              'object_name'                            => 'object_name',                            // Наименование объекта
                                              'id_type_of_object'                      => 'id_type_of_object',                      // Вид объекта
                                              'id_functional_purpose'                  => 'id_functional_purpose',                  // Функциональное назначение
                                              'id_functional_purpose_subsector'        => 'id_functional_purpose_subsector',        // Функциональное назначение. Подотрасль
                                              'id_functional_purpose_group'            => 'id_functional_purpose_group',            // Функциональное назначение. Группа
                                              'number_planning_documentation_approval' => 'number_planning_documentation_approval', // Номер утверждения документации по планировке территории
                                              'date_planning_documentation_approval'   => 'date_planning_documentation_approval',   // Дата утверждения документации по планировке территории
                                              'number_GPZU'                            => 'number_GPZU',                            // Номер ГПЗУ
                                              'date_GPZU'                              => 'date_GPZU',                              // Дата ГПЗУ
                                              'id_type_of_work'                        => 'id_type_of_work',                        // Вид работ
                                              'cadastral_number'                       => 'cadastral_number',                       // Кадастровый номер земельного участка

                                              'date_creation'                          => 'date_creation'                           // Дата создания
                                              ]);





// Маппинг кодов и названий классов
// Первый уровень вложенности соответствует типу таблиц:
//    1 - Тип таблицы:   id   id_application   file_name   hash   is_uploaded
//    используется для НЕ структурных типов хранения
//    2 - Тип таблицы:   id   id_application   id_structure_node   file_name   hash   is_uploaded
//    используется для структурных типов хранения (документации)
//
// Второй уровень вложенности соответствует названию класса для этой таблицы
define('_FILE_TABLE_MAPPING', ['1' => ['1' => 'file_grbsTable'

                                       ],
                                '2' => ['1' => 'file_documentation1Table',
                                        '2' => ''
                                       ]
                               ]);