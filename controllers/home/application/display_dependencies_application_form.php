<?php


$variablesTV = \Lib\Singles\VariableTransfer::getInstance();


// -----------------------------------------------------------------------------------------
// Установка зависимостей отображения
// -----------------------------------------------------------------------------------------

// JSON_TRUE_OR (значения разделяются символом #) - зависимый блок отработает в том случае, если в input'е хотя бы одно из перечисленных значений TRUE
// JSON_FALSE_AND (значения разделяются символом #) - зависимый блок отработает в том случае, если в input'е одновремено все перечисленные значения FALSE
$blockDependencies = [

    // Зависимость от выбранного предмета экспертизы
    'expertise_subjects' => [
        ''                   => ['estimate' => false],
        'JSON_TRUE_OR:2#3'   => ['estimate' => true],
        'JSON_FALSE_AND:2#3' => ['estimate' => false],
    ],

    // Зависимость от выбранного вида объекта
    'type_of_object' => [
        1 => ['planning_documentation_approval' => false,
            'GPZU'                            => true,
            'structureDocumentation1'         => true,
            'structureDocumentation2'         => false,
            'empty_documentation'             => false
        ],

        2 => ['planning_documentation_approval' => true,
            'GPZU'                            => false,
            'structureDocumentation1'         => false,
            'structureDocumentation2'         => true,
            'empty_documentation'             => false
        ]
    ],

    // Зависимость от ЧЕКБОКСА объект культурного наследия
    'cultural_object_type_checkbox' => [
        0 => ['cultural_object_type' => false],
        1 => ['cultural_object_type' => true]
    ],

    // Зависимость от ЧЕКБОКСА национальный проект
    'national_project_checkbox' => [
        0 => ['national_project'     => false],
        1 => ['national_project'     => true]
    ],



    // Зависимости множественных блоков
    // ----------------------------------------------------------

    'finance_type' => [
        1 => ['budget'           => true,
            'organization'       => false,
            'builder_source'     => false,
            'investor'           => false,
            'no_data'            => true,
        ],

        2 => ['budget'           => false,
            'organization'       => true,
            'builder_source'     => false,
            'investor'           => false,
            'no_data'            => true,
        ],

        3 => ['budget'           => false,
            'organization'       => false,
            'builder_source'     => true,
            'investor'           => false,
            'no_data'            => true,
        ],

        4 => ['budget'           => false,
            'organization'       => false,
            'builder_source'     => false,
            'investor'           => true,
            'no_data'            => true,
        ],
    ],

    'no_data' => [
        ''    => ['percent' => true],
        1     => ['percent' => false],
    ]

    // ----------------------------------------------------------
    // Зависимости множественных блоков




];

$variablesTV->setValue('blockDependencies', json_encode($blockDependencies));

$requireDependencies = [

    // Зависимость от выбранного вида работ
    'type_of_work' => [
        1 => ['file_grbs' => false],
        2 => ['file_grbs' => true],
        3 => ['file_grbs' => false],
        4 => ['file_grbs' => false],
        5 => ['file_grbs' => false],
        6 => ['file_grbs' => false],
        7 => ['file_grbs' => false],
    ],


];

$variablesTV->setValue('requireDependencies', json_encode($requireDependencies));