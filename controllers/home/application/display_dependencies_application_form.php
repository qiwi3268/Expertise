<?php


$variablesTV = VariableTransfer::getInstance();


// -----------------------------------------------------------------------------------------
// Установка зависимостей отображения
// -----------------------------------------------------------------------------------------

// Ключ массива - аттрирут "data-row_name" в элементе "body-card__row" - это
// главный элемент, при изменении input hidden'а которого, будет или не будет
// отображаться зависимая строчка
//
// В массиве находятся карты, где:
// ключ - значение input hidden'а главного элемента
// массив, в ключах которых data-row_name строки, которая будет отображаться (true) или скрываться (false)
//

$displayDependencies = [

    // Зависимость от выбранного "Вида объекта"
    'type_of_object' => [
        1 => ['number_planning_documentation_approval' => true,
              'date_planning_documentation_approval'   => true,
              'number_GPZU'                            => false,
              'date_GPZU'                              => false
        ],

        2 => ['number_planning_documentation_approval' => false,
              'date_planning_documentation_approval'   => false,
              'number_GPZU'                            => true,
              'date_GPZU'                              => true
        ]
    ],

    // Зависимость от ЧЕКБОКСА "Объект культурного наследия"
    'cultural_object_type_checkbox' => [
        0 => ['cultural_object_type' => false],
        1 => ['cultural_object_type' => true]
    ],

    // Зависимость от ЧЕКБОКСА "Национальный проект"
    'national_project_checkbox' => [
        0 => ['national_project'     => false,
              'federal_project'      => false,
              'date_finish_building' => false
        ],
        1 => ['national_project'      => true,
              'federal_project'       => true,
              'date_finish_building'  => true
        ],
    ],
];

$variablesTV->setValue('displayDependencies', json_encode($displayDependencies));

$blockDependencies = [

    // Зависимость от выбранного "Вида объекта"
    'type_of_object' => [
        1 => ['planning_documentation_approval' => true,
              'GPZU'                            => false,
              'structureDocumentation1'         => true,
              'structureDocumentation2'         => false,
              'empty_documentation'             => false

        ],

        2 => ['planning_documentation_approval' => false,
              'GPZU'                            => true,
              'structureDocumentation1'         => false,
              'structureDocumentation2'         => true,
              'empty_documentation'             => false
        ]
    ],

    // Зависимость от ЧЕКБОКСА "Объект культурного наследия"
    'cultural_object_type_checkbox' => [
        0 => ['cultural_object_type' => false],
        1 => ['cultural_object_type' => true]
    ],

    // Зависимость от ЧЕКБОКСА "Национальный проект"
    'national_project_checkbox' => [
        0 => ['national_project'     => false,],
        1 => ['national_project'     => true,],
    ],



];

$variablesTV->setValue('blockDependencies', json_encode($blockDependencies));