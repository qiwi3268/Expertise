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

//  4  - Передано некорректное значение справочника
//       {result, error_message : текст ошибки}
//  5  - Запрашиваемый справочник не существует
//       {result, error_message : текст ошибки}
//  6  - Одновременно переданы данные из взаимозаменяемых блоков
//       {result, error_message : текст ошибки}
//  7  - Переданы данные из зависимого блока, когда: 1 - Главный блок не заполнен, или
//                                                   2 - Главный блок имеет не то значение, при котором можно выбрать то,
//                                                       что пришло из формы зависимого блока
//       {result, error_message : текст ошибки}
//  8  - Передана некорректная дата
//       {result, error_message : текст ошибки}

//todo
//  5  - Для сохранения Предмета экспертизы необходимо наличие Цели экспертизы
//       {result, error_message : текст ошибки}
//  6  - Произошла ошибка при обработке полученного Предмета экспертизы
//       {result, error_message : текст ошибки, exception_message : текст ошибки, exception_code: код ошибки}}
//  7  - В предмете экспертизы присутствуют повторяющиеся элементы
//       {result, error_message : текст ошибки}
//  8  - Указанный Предмет экспертизы не существует
//       {result, error_message : текст ошибки}

//  9  - Указанный Предмет экспертизы не соответствует выбранной Цели экспертизы
//       {result, error_message : текст ошибки}


//

if(checkParamsPOST(_PROPERTY_IN_APPLICATION['application_id'],
                   _PROPERTY_IN_APPLICATION['expertise_purpose'],
                   _PROPERTY_IN_APPLICATION['expertise_subject']
)){


    /** @var string $P_application_id                          id-заявления */
    /** @var string $P_expertise_purpose                       Цель обращения */
    /** @var string $P_expertise_subject                       Предмет экспертизы */
    /** @var string $P_additional_information                  Доплнительная информация */
    /** @var string $P_object_name                             Наименование объекта */
    /** @var string $P_type_of_object                          Вид объекта */
    /** @var string $P_functional_purpose                      Функциональное назначение */
    /** @var string $P_number_planning_documentation_approval  Номер утверждения документации по планировке территории */
    /** @var string $P_date_planning_documentation_approval    Дата утверждения документации по планировке территории */
    /** @var string $P_number_GPZU                             Номер ГПЗУ */
    /** @var string $P_date_GPZU                               Дата ГПЗУ */

    $clearPOST = clearHtmlArr($_POST);
    extract($clearPOST, EXTR_PREFIX_ALL, 'P');

    // -----------------------------------------------------------------------------------------------------------------
    // Зона валидации формы
    //
    // Общие проверки:
    // 1 - проверка на существование заявления
    // 2 - проверка на наличие прав доступа к заявлению
    //
    // В этой области проверяется, что данные из формы имеют корректную структуру:
    // 1 - числовые поля содержат число (если float, то преобразуется к int'у),
    //     если строку, которая не преобразуется к числу - то будет ошибка при передаче этого параметра в метод БД
    // 2 - записи со значениями числовых полей действительно существуют в БД
    // 3 - JSON строки являются валидными с точки зрения синтаксиса
    // 4 - JSON строки не содержат повторяющихся элементов в массиве
    //
    // Проверка на соответствие бизнес-логики
    // 1 - поле1, от которого зависит поле2, должно обязательно существовать, если существует поле2
    // -----------------------------------------------------------------------------------------------------------------
    //

    // Преобразуем значение из формы явно к типу int
    $form_applicationID = (int)$P_application_id;

    // Проверка на доступ к заявлению и его существование
    $applicationAssoc = ApplicationsTable::getAssocById($form_applicationID);

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
    $FormHandler = new ApplicationFormHandler($applicationAssoc, $clearPOST);


    // Проверка Цели обращения -----------------------------------------------------------------
    //
    if($P_expertise_purpose !== ''){

        $expertisePurposeValidateResult = $FormHandler->validateSingleMisc($P_expertise_purpose, 'misc_expertisePurposeTable');

        if($expertisePurposeValidateResult['error']){

            switch($expertisePurposeValidateResult['error_code']){
                case 1:
                    exit(json_encode(['result'        => 4,
                                      'error_message' => 'Передано некорректное значение для Цели экспертизы'
                    ]));
                case 2:
                    exit(json_encode(['result'        => 5,
                                      'error_message' => 'Запрашиваемый справочник не существует'
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
    if($P_expertise_subject !== ''){

        // Попытка заполнить Предмет экспертизы без выбранной Цели экспертизы
        if(!expertise_purpose_exist){

            exit(json_encode(['result'        => 5,
                              'error_message' => 'Для сохранения Предмета экспертизы необходимо наличие Цели экспертизы'
                             ]));
        }

        $form_expertiseSubjects = [];
        try{

            $form_expertiseSubjects = json_decode($P_expertise_subject, false, 2, JSON_THROW_ON_ERROR);
        }catch(jsonException $ex){

            exit(json_encode(['result'            => 6,
                              'error_message'     => 'Произошла ошибка при обработке полученного Предмета экспертизы',
                              'exception_message' => $ex->getMessage(),
                              'exception_code'    => $ex->getCode()
                             ]));
        }


        foreach($form_expertiseSubjects as &$id){

            // Количество таких-же элементов в массиве, как текущий (включая его)
            // отключаем строгую проверку, т.к. одинаковые, но ранее преобразованные к int'у id
            // не будут равными

            //todo потом проверить еще раз, что одинаковые ключи нельзя
            $countIds = count(array_keys($form_expertiseSubjects, $id, false));

            // Проверка на то, что у нас во входном json-массиве Предметов экспертизы нет повторяющихся элементов
            if($countIds > 1){
                exit(json_encode(['result'        => 7,
                                  'error_message' => "В предмете экспертизы присутствуют повторяющиеся элементы с id: $id"
                                 ]));
            }

            // Преобразуем значение из формы явно к типу int
            $id = (int)$id;

            $assoc = misc_expertiseSubjectTable::getAssocById($id);

            //todo тут можно написать метод для проверки существования записи, а не получать ассок
            if(is_null($assoc)){
                exit(json_encode(['result'        => 8,
                                  'error_message' => "Указанный Предмет экспертизы id: $id не существует"
                                 ]));
            }

            // Проверка на то, что выбранный Предмет экспертизы принадлежит выбранной Цели обращения
            if(!misc_expertiseSubjectTable::checkExist_CORR_ExpertisePurposeByIds($form_expertisePurposeID, $id)){
                exit(json_encode(['result'        => 9,
                                  'error_message' => "Указанный Предмет экспертизы не соответствует выбранной Цели экспертизы"
                                 ]));
            }
        }
        unset($id);

        define('expertise_subjects_exist', true);
    }else{
        define('expertise_subjects_exist', false);
    }


    // Проверка Вида объекта -------------------------------------------------------------------
    //
    if($P_type_of_object !== ''){

        $typeOfObjectValidateResult =  $FormHandler->validateSingleMisc($P_type_of_object, 'misc_typeOfObjectTable');

        if($typeOfObjectValidateResult['error']){

            switch($typeOfObjectValidateResult['error_code']){
                case 1:
                    exit(json_encode(['result'        => 4,
                                      'error_message' => 'Передано некорректное значение для Вида объекта'
                    ]));
                case 2:
                    exit(json_encode(['result'        => 5,
                                      'error_message' => 'Запрашиваемый справочник не существует'
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

        $functionalPurposeValidateResult = $FormHandler->validateSingleMisc($P_functional_purpose, 'misc_functionalPurposeTable');

        if($functionalPurposeValidateResult['error']){

            switch($functionalPurposeValidateResult['error_code']){
                case 1:
                    exit(json_encode(['result'        => 4,
                                      'error_message' => 'Передано некорректное значение для Функционального назначения'
                    ]));
                case 2:
                    exit(json_encode(['result'        => 5,
                                      'error_message' => 'Запрашиваемый справочник не существует'
                    ]));
            }
        }

        // int'овое значение из формы
        $form_functionalPurposeID = $functionalPurposeValidateResult['int_formValue'];

        define('functional_purpose_exist', true);
    }else{

        define('functional_purpose_exist', false);
    }


    // Проверка блока Номера и Дат -------------------------------------------------------------

    // Из формы одновременное пришли данные из блока Утверждения документации по планировке территории и ГПЗУ
    if(($P_number_planning_documentation_approval !== '' || $P_date_planning_documentation_approval !== '') && ($P_number_GPZU !== '' || $P_date_GPZU !== '')){

        //todo протестить эту ветку
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
        if($P_date_planning_documentation_approval !== '' && !$FormHandler->validateDate($P_date_planning_documentation_approval)){

            exit(json_encode(['result'        => 8,
                              'error_message' => 'Дата утверждения документации по планировке территории некорректна'
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
        if($P_date_GPZU !== '' && !$FormHandler->validateDate($P_date_GPZU)){

            exit(json_encode(['result'        => 8,
                              'error_message' => 'Дата ГПЗУ некорректна'
            ]));
        }
    }


    // Номера утверждения документации по планировке территории
    // И
    // Дата утверждения документации по планировке территории
    //





    // -----------------------------------------------------------------------------------------------------------------
    // Зона сохранения заявления в БД
    //
    // Имеет bool константы:
    // 1 - expertise_purpose_exist   : передана Цель экспертизы
    // 2 - expertise_subjects_exist  : передан(ы) Предметы экспертизы
    // 3 - type_of_object_exist      : передан Вид объекта
    // 4 - functional_purpose_exist  : передано Функциональное назначение
    //
    // Если константы true, то определены:
    // 1 - form_expertisePurposeID          int : id выбранной Цели обращения
    // 2 - form_expertiseSubjects array[int...] : массив с выбранными предметами (int) экспертизы
    // 3 - form_typeOfObjectID              int : id выбранного Вида объекта
    // 4 - form_functionalPurposeID         int : id выбранного Функционального назначение
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
    $FormHandler->addTextInputValueToUpdate($P_additional_information, _COLUMN_NAME_IN_APPLICATIONS_TABLE['additional_information'], $dataToUpdate);


    // Наименование объекта (текстовое поле) ---------------------------------------------------
    $FormHandler->addTextInputValueToUpdate($P_object_name, _COLUMN_NAME_IN_APPLICATIONS_TABLE['object_name'], $dataToUpdate);


    // Вид объекта (справочник, нельзя сбросить) -----------------------------------------------
    if(type_of_object_exist && $form_typeOfObjectID !== $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_object']]){
        $dataToUpdate[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_type_of_object']] = $form_typeOfObjectID;
    }


    // Функциональное назначение (справочник, нельзя сбросить) ----------------------------------
    if(functional_purpose_exist && $form_functionalPurposeID !== $applicationAssoc[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose']]){
        $dataToUpdate[_COLUMN_NAME_IN_APPLICATIONS_TABLE['id_functional_purpose']] = $form_functionalPurposeID;
    }


    // Номер утверждения документации по планировке территории (текстовое поле) -----------------
    $FormHandler->addTextInputValueToUpdate($P_number_planning_documentation_approval, _COLUMN_NAME_IN_APPLICATIONS_TABLE['number_planning_documentation_approval'], $dataToUpdate);


    // Дата утверждения документации по планировке территории (текстовое поле, календарь) -------
    $FormHandler->addTextInputValueToUpdate(strtotime($P_date_planning_documentation_approval), _COLUMN_NAME_IN_APPLICATIONS_TABLE['date_planning_documentation_approval'], $dataToUpdate);


    // Номер ГПЗУ (текстовое поле) -------------------------------------------------------------
    $FormHandler->addTextInputValueToUpdate($P_number_GPZU, _COLUMN_NAME_IN_APPLICATIONS_TABLE['number_GPZU'], $dataToUpdate);


    // Дата ГПЗУ (текстовое поле, календарь) ---------------------------------------------------
    $FormHandler->addTextInputValueToUpdate(strtotime($P_date_GPZU), _COLUMN_NAME_IN_APPLICATIONS_TABLE['date_GPZU'], $dataToUpdate);






    // блок сохранения в БД

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