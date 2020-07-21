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
    _PROPERTY_IN_APPLICATION['type_of_object'] => [
        1 => [_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] => true,
            _PROPERTY_IN_APPLICATION['date_planning_documentation_approval']   => true,
            _PROPERTY_IN_APPLICATION['number_GPZU']                            => false,
            _PROPERTY_IN_APPLICATION['date_GPZU']                              => false
        ],
        
        2 => [_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] => false,
            _PROPERTY_IN_APPLICATION['date_planning_documentation_approval']   => false,
            _PROPERTY_IN_APPLICATION['number_GPZU']                            => true,
            _PROPERTY_IN_APPLICATION['date_GPZU']                              => true
        ]
    ],
    
    // Зависимость от ЧЕКБОКСА "Объект культурного наследия"
    _PROPERTY_IN_APPLICATION['cultural_object_type_checkbox'] => [
        0 => [_PROPERTY_IN_APPLICATION['cultural_object_type'] => false],
        1 => [_PROPERTY_IN_APPLICATION['cultural_object_type'] => true]
    ],
    
    // Зависимость от ЧЕКБОКСА "Национальный проект"
    _PROPERTY_IN_APPLICATION['national_project_checkbox'] => [
        0 => [_PROPERTY_IN_APPLICATION['national_project']     => false,
            _PROPERTY_IN_APPLICATION['federal_project']      => false,
            _PROPERTY_IN_APPLICATION['date_finish_building'] => false
        ],
        1 => [_PROPERTY_IN_APPLICATION['national_project']     => true,
            _PROPERTY_IN_APPLICATION['federal_project']       => true,
            _PROPERTY_IN_APPLICATION['date_finish_building']  => true
        ],
    ],
];

$variablesTV->setValue('displayDependencies', json_encode($displayDependencies));