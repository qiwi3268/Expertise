<?php

// API предназначен для сохранения анкеты заявления
//
//API result:
//	1  - Нет обязательных параметров POST запроса
//       {result, error_message : текст ошибки}
//	2  - У заявителя отсутствуют права на сохранение заявления
//       {result, error_message : текст ошибки}
//	3  - Заявление не существует
//       {result, error_message : текст ошибки}

//  4  - Передано некорректное значение (справочника / даты / json'а)
//       {result, error_message : текст ошибки}
//  5  - Запрашиваемый справочник не существует
//       {result, error_message : текст ошибки}
//  6  - Одновременно переданы данные из взаимозаменяемых блоков
//       {result, error_message : текст ошибки}
//  7  - Переданы данные из зависимого блока, когда: 1 - Главный блок не заполнен, или
//                                                   2 - Главный блок имеет не то значение, при котором можно выбрать то,
//                                                       что пришло из формы зависимого блока
//       {result, error_message : текст ошибки}


if(checkParamsPOST(_PROPERTY_IN_APPLICATION['application_id'],
                   _PROPERTY_IN_APPLICATION['expertise_purpose'],
                   _PROPERTY_IN_APPLICATION['expertise_subjects'],
                   _PROPERTY_IN_APPLICATION['additional_information'],
                   _PROPERTY_IN_APPLICATION['object_name'],
                   _PROPERTY_IN_APPLICATION['type_of_object'],
                   _PROPERTY_IN_APPLICATION['functional_purpose'],
                   _PROPERTY_IN_APPLICATION['number_planning_documentation_approval'],
                   _PROPERTY_IN_APPLICATION['date_planning_documentation_approval'],
                   _PROPERTY_IN_APPLICATION['number_GPZU'],
                   _PROPERTY_IN_APPLICATION['date_GPZU'],
                   _PROPERTY_IN_APPLICATION['type_of_work'],
                   _PROPERTY_IN_APPLICATION['cadastral_number']
)){


    /** @var string $P_application_id                          id-заявления */
    /** @var string $P_expertise_purpose                       Цель обращения */
    /** @var string $P_expertise_subjects                      Предмет(ы) экспертизы */
    /** @var string $P_additional_information                  Доплнительная информация */
    /** @var string $P_object_name                             Наименование объекта */
    /** @var string $P_type_of_object                          Вид объекта */
    /** @var string $P_functional_purpose                      Функциональное назначение */
    /** @var string $P_number_planning_documentation_approval  Номер утверждения документации по планировке территории */
    /** @var string $P_date_planning_documentation_approval    Дата утверждения документации по планировке территории */
    /** @var string $P_number_GPZU                             Номер ГПЗУ */
    /** @var string $P_date_GPZU                               Дата ГПЗУ */
    /** @var string $P_type_of_work                            Вид работ */
    /** @var string $P_cadastral_number                        Кадастровый номер земельного участка */

    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');

    // -----------------------------------------------------------------------------------------------------------------
    // Зона валидации формы
    // -----------------------------------------------------------------------------------------------------------------
    //

    // Преобразуем значение из формы явно к типу int
    $form_applicationID = (int)$P_application_id;

    // Проверка на доступ к заявлению и его существование
    $applicationAssoc = ApplicationsTable::getFlatAssocById($form_applicationID);

    $applicationExist = !is_null($applicationAssoc);

    if(Session::isApplicant()){

        // Проверка прав заявителя на доступ к сохранению заявления
        $canSaveApplication = ApplicationHelper::checkApplicantRightsToSaveApplication($form_applicationID);

        // Заявителю говорим, что нет прав, даже в том случае, если заявление не существует,
        // чтобы не могли через html проверять заявления
        if(!$applicationExist || !$canSaveApplication){

            exit(json_encode(['result'        => 2,
                              'error_message' => "У заявителя отсуствуют права на сохранение заявления id: $form_applicationID"
            ]));
        }

    // Сотрудник
    }else{
        // todo проверить эту ветку на сотруднике
        if(!$applicationExist){

            exit(json_encode(['result'        => 3,
                              'error_message' => "Заявление id: $form_applicationID не существует"
            ]));
        }
    }

    // Объект класса-обработчика
    $formHandler = new ApplicationFormHandler($applicationAssoc);


    // Проверка Цели обращения -----------------------------------------------------------------
    //
    if($P_expertise_purpose !== ''){

        $expertisePurposeValidateResult = $formHandler->validateSingleMisc($P_expertise_purpose, 'misc_expertisePurposeTable');

        if($expertisePurposeValidateResult['error']){

            switch($expertisePurposeValidateResult['error_code']){
                case 1:
                    exit(json_encode(['result'        => 4,
                                      'error_message' => 'Передано некорректное значение Цели обращения'
                    ]));
                case 2:
                    exit(json_encode(['result'        => 5,
                                      'error_message' => 'Запрашиваемый справочник Цели обращения не существует'
                    ]));
            }
        }

        // int'овое значение из формы
        $form_expertisePurposeID = $expertisePurposeValidateResult['int_formValue'];

        define('expertise_purpose_exist', true);
    }else{

        define('expertise_purpose_exist', false);
    }


    // Проверка Предметов экспертизы -----------------------------------------------------------
    //
    if($P_expertise_subjects !== ''){

        // Попытка заполнить Предмет экспертизы без выбранной Цели обращения
        if(!expertise_purpose_exist){

           exit(json_encode(['result'        => 7,
                             'error_message' => 'Предмет экспертизы не может быть заполнен при невыбранной Цели обращения'
           ]));
        }

        $expertiseSubjectsJSONvalidateResult = $formHandler->validateNumericalJson($P_expertise_subjects);

        if($expertiseSubjectsJSONvalidateResult['error']){

            $errorMessage = 'Передано некорректное значение Предмета экспертизы.';

            switch($expertiseSubjectsJSONvalidateResult['error_code']){
                case 1: // Ошибка при парсинге json'а
                    $errorMessage .= ' Ошибка при работе с переданным объектом.';
                    $errorMessage .= ' Message: '.$expertiseSubjectsJSONvalidateResult['exception_message'];
                    $errorMessage .= ', Code: '.$expertiseSubjectsJSONvalidateResult['exception_code'];
                    break;
                case 2: // В массиве присутствуют нечисловые элементы
                    $errorMessage .= ' В объекте присутствуют нечисловые элементы';
                    break;
                case 3: // В массиве присутствуют одинаковые элементы
                    $errorMessage .= ' В объекте присутствуют одинаковые элементы';
                    break;
            }

            exit(json_encode(['result'        => 4,
                              'error_message' => $errorMessage
            ]));
        }

        // int'овый массив из формы
        $form_expertiseSubjects = $expertiseSubjectsJSONvalidateResult['int_formArray'];

        foreach($form_expertiseSubjects as $id){

            $expertiseSubjectValidateResult = $formHandler->validateDependentMisc($form_expertisePurposeID, $id, 'misc_expertiseSubjectTable');

            if($expertiseSubjectValidateResult['error']){

                switch($expertiseSubjectValidateResult['error_code']){
                    case 1: // Передано некорректное значение зависимого справочника
                        exit(json_encode(['result'        => 4,
                                          'error_message' => 'Передано некорректное значение Предмета экспертизы'
                        ]));
                    case 2: // Запрашиваемая в форме зависимость не существует
                        exit(json_encode(['result'        => 5,
                                          'error_message' => 'Запрашиваемый справочник Предмета экспертизы не существует'
                        ]));
                }
            }
        }

        define('expertise_subjects_exist', true);
    }else{
        define('expertise_subjects_exist', false);
    }


    // Проверка Вида объекта -------------------------------------------------------------------
    //
    if($P_type_of_object !== ''){

        $typeOfObjectValidateResult = $formHandler->validateSingleMisc($P_type_of_object, 'misc_typeOfObjectTable');

        if($typeOfObjectValidateResult['error']){

            switch($typeOfObjectValidateResult['error_code']){
                case 1: // Передано некорректное значение справочника
                    exit(json_encode(['result'        => 4,
                                      'error_message' => 'Передано некорректное значение Вида объекта'
                    ]));
                case 2: // Запрашиваемый справочник не существует
                    exit(json_encode(['result'        => 5,
                                      'error_message' => 'Запрашиваемый справочник Вида объекта не существует'
                    ]));
            }
        }

        // int'овое значение из формы
        $form_typeOfObjectID = $typeOfObjectValidateResult['int_formValue'];

        define('type_of_object_exist', true);
    }else{

        define('type_of_object_exist', false);
    }


    // Проверка Функционального назначения -----------------------------------------------------
    //
    if($P_functional_purpose !== ''){

        $functionalPurposeValidateResult = $formHandler->validateSingleMisc($P_functional_purpose, 'misc_functionalPurposeTable');

        if($functionalPurposeValidateResult['error']){

            switch($functionalPurposeValidateResult['error_code']){
                case 1: // Передано некорректное значение справочника
                    exit(json_encode(['result'        => 4,
                                      'error_message' => 'Передано некорректное значение Функционального назначения'
                    ]));
                case 2: // Запрашиваемый справочник не существует
                    exit(json_encode(['result'        => 5,
                                      'error_message' => 'Запрашиваемый справочник Функционального назначения не существует'
                    ]));
            }
        }

        // int'овое значение из формы
        $form_functionalPurposeID = $functionalPurposeValidateResult['int_formValue'];

        define('functional_purpose_exist', true);
    }else{

        define('functional_purpose_exist', false);
    }



    // Проверка блока Номера и Даты ------------------------------------------------------------
    //
    // Из формы одновременное пришли данные из блока Утверждения документации по планировке территории и ГПЗУ
    if(($P_number_planning_documentation_approval !== '' || $P_date_planning_documentation_approval !== '') && ($P_number_GPZU !== '' || $P_date_GPZU !== '')){

        exit(json_encode(['result'        => 6,
                          'error_message' => 'Одновременно переданы данные из блока Утверждения документации по планировке территории и ГПЗУ'
        ]));
    }

    if($P_number_planning_documentation_approval !== '' || $P_date_planning_documentation_approval !== ''){

        // Заполнены данные при невыбранном Виде объекта или Вид объекта не того типа
        if(!type_of_object_exist || $form_typeOfObjectID !== 1){

            exit(json_encode(['result'        => 7,
                              'error_message' => 'Данные из блока Утверждения документации по планировке территории не могут быть заполнены при указанном Виде объекта'
            ]));
        }

        // Валидация Даты
        if($P_date_planning_documentation_approval !== '' && !$formHandler->validateDate($P_date_planning_documentation_approval)){

            exit(json_encode(['result'        => 4,
                              'error_message' => 'Передано некорректное значение даты Утверждения документации по планировке территории'
            ]));
        }

    }elseif($P_number_GPZU !== '' || $P_date_GPZU !== ''){

        // Заполнены данные при невыбранном Виде объекта или Вид объекта не того типа
        if(!type_of_object_exist || $form_typeOfObjectID !== 2){

            exit(json_encode(['result'        => 7,
                              'error_message' => 'Данные из блока ГПЗУ не могут быть заполнены при указанном Виде объекта'
            ]));
        }

        // Валидация Даты
        if($P_date_GPZU !== '' && !$formHandler->validateDate($P_date_GPZU)){

            exit(json_encode(['result'        => 4,
                              'error_message' => 'Передано некорректное значение даты ГПЗУ'
            ]));
        }
    }



    // Проверка Вида работ ---------------------------------------------------------------------
    //
    if($P_type_of_work !== ''){

        if(!expertise_purpose_exist){
            exit(json_encode(['result'        => 7,
                              'error_message' => 'Вид работ не может быть заполнен при невыбранной Цели обращения'
            ]));
        }

        $typeOfWorkValidateResult = $formHandler->validateDependentMisc($form_expertisePurposeID, $P_type_of_work, 'misc_typeOfWorkTable');


        if($typeOfWorkValidateResult['error']){

            switch($typeOfWorkValidateResult['error_code']){
                case 1: // Передано некорректное значение зависимого справочника
                    exit(json_encode(['result'        => 4,
                                      'error_message' => 'Передано некорректное значение Вида работ'
                    ]));
                case 2: // Запрашиваемая в форме зависимость не существует
                    exit(json_encode(['result'        => 5,
                                      'error_message' => 'Запрашиваемый справочник Вида работ не существует'
                    ]));
            }
        }

        // int'овое значение из формы
        $form_typeOfWorkID = $typeOfWorkValidateResult['int_formValueDependent'];

        define('type_of_work_exist', true);
    }else{

        define('type_of_work_exist', false);
    }



    // -----------------------------------------------------------------------------------------------------------------
    // Зона сохранения заявления в БД
    //
    // Имеет bool константы:
    // 1 - expertise_purpose_exist   : передана Цель обращения
    // 2 - expertise_subjects_exist  : передан(ы) Предметы экспертизы
    // 3 - type_of_object_exist      : передан Вид объекта
    // 4 - functional_purpose_exist  : передано Функциональное назначение
    // 5 - type_of_work_exist        : передан Вид работ
    //
    // Если константы true, то определены:
    // 1 - form_expertisePurposeID          int : id выбранной Цели обращения
    // 2 - form_expertiseSubjects array[int...] : массив с выбранными предметами (int) экспертизы
    // 3 - form_typeOfObjectID              int : id выбранного Вида объекта
    // 4 - form_functionalPurposeID         int : id выбранного Функционального назначение
    // 5 - form_typeOfWorkID                int : id выбранного Вида работ
    //
    // -----------------------------------------------------------------------------------------------------------------

    // Формируем ассоциативный массив данных, которые необходимо обновить в БД
    // ключ     - название столюца в БС
    // значение - новое значение, которое будет установлено
    $dataToUpdate = [];


    // Цель обращения (справочник, нельзя сбросить) --------------------------------------------
    if(expertise_purpose_exist && $form_expertisePurposeID !== $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_expertise_purpose']]){
        $dataToUpdate[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_expertise_purpose']] = $form_expertisePurposeID;
    }


    // Предмет экспертизы (радио, можно сбросить) ----------------------------------------------

    // Предметы экспертизы, которые уже есть у заявления
    $db_expertiseSubjects = ExpertiseSubjectTable::getIdsByIdApplication($form_applicationID);

    $db_expertiseSubjects ??= [];    // Если с БД пришел null, то приравниваем к пустому массиву для array_diff
    $expertiseSubjectsToDelete = []; // Массив с id Предметов экспертизы, которые нужно удалить
    $expertiseSubjectsToCreate = []; // Массив с id Предметов экспертизы, которые нужно создать к заявлению

    if(expertise_subjects_exist){

        // id Предметов, которые есть в БД, но нет в пришедшей форме
        $expertiseSubjectsToDelete = array_diff($db_expertiseSubjects, $form_expertiseSubjects);

        // id Предметов, которые есть в пришедшей форме, но нет в БД
        $expertiseSubjectsToCreate = array_diff($form_expertiseSubjects, $db_expertiseSubjects);

    // Из формы пришло пустое значение, удаляем все Предметы экспертизы
    }else{

        $expertiseSubjectsToDelete = $db_expertiseSubjects;
    }

    // Удаляем и записываем в БД новые записи о Предмете экспертизы
    foreach($expertiseSubjectsToDelete As $id){
        ExpertiseSubjectTable::delete($form_applicationID, $id);
    }
    foreach($expertiseSubjectsToCreate As $id){
        ExpertiseSubjectTable::create($form_applicationID, $id);
    }


    // Дополнительная информация (текстовое поле) ----------------------------------------------
    $formHandler->addValueToUpdate($P_additional_information, _COLUMN_NAME_IN_APPLICATIONS_TABLE['additional_information'], $dataToUpdate);


    // Наименование объекта (текстовое поле) ---------------------------------------------------
    $formHandler->addValueToUpdate($P_object_name, _COLUMN_NAME_IN_APPLICATIONS_TABLE['object_name'], $dataToUpdate);


    // Вид объекта (справочник, нельзя сбросить) -----------------------------------------------
    if(type_of_object_exist && $form_typeOfObjectID !== $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_object']]){
        $dataToUpdate[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_object']] = $form_typeOfObjectID;
    }


    // Функциональное назначение (справочник, нельзя сбросить) ----------------------------------
    if(functional_purpose_exist && $form_functionalPurposeID !== $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose']]){
        $dataToUpdate[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose']] = $form_functionalPurposeID;
    }


    // Номер утверждения документации по планировке территории (текстовое поле) -----------------
    $formHandler->addValueToUpdate($P_number_planning_documentation_approval, _COLUMN_NAME_IN_APPLICATIONS_TABLE['number_planning_documentation_approval'], $dataToUpdate);


    // Дата утверждения документации по планировке территории (текстовое поле, календарь) -------
    $formHandler->addValueToUpdate(strtotime($P_date_planning_documentation_approval), _COLUMN_NAME_IN_APPLICATIONS_TABLE['date_planning_documentation_approval'], $dataToUpdate);


    // Номер ГПЗУ (текстовое поле) -------------------------------------------------------------
    $formHandler->addValueToUpdate($P_number_GPZU, _COLUMN_NAME_IN_APPLICATIONS_TABLE['number_GPZU'], $dataToUpdate);


    // Дата ГПЗУ (текстовое поле, календарь) ---------------------------------------------------
    $formHandler->addValueToUpdate(strtotime($P_date_GPZU), _COLUMN_NAME_IN_APPLICATIONS_TABLE['date_GPZU'], $dataToUpdate);


    // Вид работ (справочник, можно сбросить)
    if(type_of_work_exist){
        $formHandler->addValueToUpdate($form_typeOfWorkID, _COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_work'], $dataToUpdate);
    }else{
        $formHandler->addValueToUpdate('', _COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_work'], $dataToUpdate);
    }


    // Кадастровый номер земельного участка (текстовое поле) -----------------------------------
    $formHandler->addValueToUpdate($P_cadastral_number, _COLUMN_NAME_IN_APPLICATIONS_TABLE['cadastral_number'], $dataToUpdate);



    // Сохранение в БД полей заявления ---------------------------------------------------------
    //
    // Вызываем умное сохранение, если данные в заявлении поменялись
    if(!empty($dataToUpdate)){

        // Обновляем флаг сохраненности заявления для новых заявлений
        if(!$applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['is_saved']]){
            $dataToUpdate[_COLUMN_NAME_IN_APPLICATIONS_TABLE['is_saved']] = 1;
        }
        ApplicationsTable::smartUpdateById($dataToUpdate, $form_applicationID);
    }

















    exit(json_encode(['result'        => 777,
                      'error_message' => 'Все хорошо'
    ]));
}
exit(json_encode(['result'        => 1,
                  'error_message' => 'Нет обязательных параметров POST запроса'
]));