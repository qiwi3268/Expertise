<?php


// API предназначен для динамической сохранения анкеты заявления
//
// API result:
//  1  - Нет обязательных параметров POST запроса
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
//  8 -  Успешное сохранение
//       {result}
//  9 -  Непредвиденная ошибка
//       {result, message : текст ошибки, code: код ошибки}

if(!checkParamsPOST('id_application',
                   'expertise_purpose',
                   'expertise_subjects',
                   'additional_information',
                   'object_name',
                   'type_of_object',
                   'functional_purpose',
                   'functional_purpose_subsector',
                   'functional_purpose_group',
                   'number_planning_documentation_approval',
                   'date_planning_documentation_approval',
                   'number_GPZU',
                   'date_GPZU',
                   'type_of_work',
                   'cadastral_number',
                   'cultural_object_type_checkbox',
                   'cultural_object_type',
                   'national_project_checkbox',
                   'national_project',
                   'federal_project',
                   'date_finish_building',
                   'curator'
)){
    exit(json_encode(['result'        => 1,
                      'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}


try{
    
    /** @var string $P_id_application id-заявления */
    /** @var string $P_expertise_purpose Цель обращения */
    /** @var string $P_expertise_subjects Предмет(ы) экспертизы */
    /** @var string $P_additional_information Доплнительная информация */
    /** @var string $P_object_name Наименование объекта */
    /** @var string $P_type_of_object Вид объекта */
    /** @var string $P_functional_purpose Функциональное назначение */
    /** @var string $P_functional_purpose_subsector Функциональное назначение. Подотрасль */
    /** @var string $P_functional_purpose_group Функциональное назначение. Группа */
    /** @var string $P_number_planning_documentation_approval Номер утверждения документации по планировке территории */
    /** @var string $P_date_planning_documentation_approval Дата утверждения документации по планировке территории */
    /** @var string $P_number_GPZU Номер ГПЗУ */
    /** @var string $P_date_GPZU Дата ГПЗУ */
    /** @var string $P_type_of_work Вид работ */
    /** @var string $P_cadastral_number Кадастровый номер земельного участка */
    /** @var string $P_cultural_object_type_checkbox Тип объекта культурного наследия (ЧЕКБОКС) */
    /** @var string $P_cultural_object_type Тип объекта культурного наследия */
    /** @var string $P_national_project_checkbox Национальный проект (ЧЕКБОКС) */
    /** @var string $P_national_project Национальный проект */
    /** @var string $P_federal_project Федеральный проект */
    /** @var string $P_date_finish_building Дата окончания строительства */
    /** @var string $P_curator Куратор */
    
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');
    
    
    // -----------------------------------------------------------------------------------------------------------------
    // Зона валидации формы
    // -----------------------------------------------------------------------------------------------------------------
    
    // Преобразуем значение из формы явно к типу int
    $form_applicationID = (int)$P_id_application;
    
    // Проверка на доступ к заявлению и его существование
    $applicationAssoc = ApplicationsTable::getFlatAssocById($form_applicationID);
    
    $applicationExist = !is_null($applicationAssoc);
    
    if(Session::isApplicant()){
        
        // Проверка прав заявителя на доступ к сохранению заявления
        // todo переделать на вызов из специализированного класса
        $canSaveApplication = ApplicationHelper::checkApplicantRightsToSaveApplication($form_applicationID);
        
        // Заявителю говорим, что нет прав, даже в том случае, если заявление не существует,
        // чтобы не могли через html проверять заявления
        if(!$applicationExist || !$canSaveApplication){
            
            exit(json_encode(['result' => 2, 'error_message' => "У заявителя отсуствуют права на сохранение заявления id: $form_applicationID"]));
        }
        
        // Сотрудник
    }else{
        
        if(!$applicationExist){
            
            exit(json_encode(['result' => 3, 'error_message' => "Заявление id: $form_applicationID не существует"]));
        }
    }
    
    // Объект класса-обработчика
    $formHandler = new SaveHandler($applicationAssoc);
    
    
    // Проверка Цели обращения -----------------------------------------------------------------
    //
    if($P_expertise_purpose !== ''){
        
        $expertisePurposeValidateResult = $formHandler->validateSingleMisc($P_expertise_purpose, 'misc_expertisePurposeTable');
        
        if($expertisePurposeValidateResult['error']){
            
            switch($expertisePurposeValidateResult['error_code']){
                case 1:
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Цели обращения']));
                case 2:
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Цели обращения не существует']));
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
            
            exit(json_encode(['result' => 7, 'error_message' => 'Предмет экспертизы не может быть заполнен при невыбранной Цели обращения']));
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
            
            exit(json_encode(['result' => 4, 'error_message' => $errorMessage]));
        }
        
        // int'овый массив из формы
        $form_expertiseSubjects = $expertiseSubjectsJSONvalidateResult['int_formArray'];
        
        foreach($form_expertiseSubjects as $id){
            
            // Проверка каждого выбранного Предмета экспертизы на зависимость от выбранной Цели обращения
            $expertiseSubjectValidateResult = $formHandler->validateDependentMisc($form_expertisePurposeID, $id, 'misc_expertiseSubjectTable');
            
            if($expertiseSubjectValidateResult['error']){
                
                switch($expertiseSubjectValidateResult['error_code']){
                    case 1: // Передано некорректное значение зависимого справочника
                        exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Предмета экспертизы']));
                    case 2: // Запрашиваемая в форме зависимость не существует
                        exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Предмета экспертизы не существует']));
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
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Вида объекта']));
                case 2: // Запрашиваемый справочник не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Вида объекта не существует']));
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
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Функционального назначения']));
                case 2: // Запрашиваемый справочник не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Функционального назначения не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_functionalPurposeID = $functionalPurposeValidateResult['int_formValue'];
        
        define('functional_purpose_exist', true);
    }else{
        
        define('functional_purpose_exist', false);
    }
    
    
    // Проверка Функциональное назначение. Подотрасль ------------------------------------------
    //
    if($P_functional_purpose_subsector !== ''){
        
        if(!functional_purpose_exist){
            exit(json_encode(['result' => 7, 'error_message' => 'Функциональное назначение. Подотрасль не может быть заполнена при невыбранном Функциональном назначении']));
        }
        
        $functionalPurposeSubsectorValidateResult = $formHandler->validateDependentMisc($form_functionalPurposeID, $P_functional_purpose_subsector, 'misc_functionalPurposeSubsectorTable');
        
        if($functionalPurposeSubsectorValidateResult['error']){
            
            switch($functionalPurposeSubsectorValidateResult['error_code']){
                case 1: // Передано некорректное значение зависимого справочника
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Функциональное назначение. Подотрасль']));
                case 2: // Запрашиваемая в форме зависимость не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Функциональное назначение. Подотрасль не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_functionalPurposeSubsectorID = $functionalPurposeSubsectorValidateResult['int_formValueDependent'];
        
        define('functional_purpose_subsector_exist', true);
    }else{
        
        define('functional_purpose_subsector_exist', false);
    }
    
    
    // Проверка Функциональное назначение. Группа ----------------------------------------------
    //
    if($P_functional_purpose_group !== ''){
        
        if(!functional_purpose_subsector_exist){
            exit(json_encode(['result' => 7, 'error_message' => 'Функциональное назначение. Группа не может быть заполнена при невыбранной Функциональном назначении. Подотрасль']));
        }
        
        $functionalPurposeGroupValidateResult = $formHandler->validateDependentMisc($form_functionalPurposeSubsectorID, $P_functional_purpose_group, 'misc_functionalPurposeGroupTable');
        
        if($functionalPurposeGroupValidateResult['error']){
            
            switch($functionalPurposeGroupValidateResult['error_code']){
                case 1: // Передано некорректное значение зависимого справочника
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Функциональное назначение. Группа']));
                case 2: // Запрашиваемая в форме зависимость не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Функциональное назначение. Группа не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_functionalPurposeGroupID = $functionalPurposeGroupValidateResult['int_formValueDependent'];
        
        define('functional_purpose_group_exist', true);
    }else{
        
        define('functional_purpose_group_exist', false);
    }
    
    
    // Проверка блока Номера и Даты ------------------------------------------------------------
    //
    // Из формы одновременное пришли данные из блока Утверждения документации по планировке территории и ГПЗУ
    if(($P_number_planning_documentation_approval !== '' || $P_date_planning_documentation_approval !== '') && ($P_number_GPZU !== '' || $P_date_GPZU !== '')){
        
        exit(json_encode(['result' => 6, 'error_message' => 'Одновременно переданы данные из блока Утверждения документации по планировке территории и ГПЗУ']));
    }
    
    if($P_number_planning_documentation_approval !== '' || $P_date_planning_documentation_approval !== ''){
        
        // Заполнены данные при невыбранном Виде объекта или Вид объекта не того типа
        if(!type_of_object_exist || $form_typeOfObjectID !== 1){
            
            exit(json_encode(['result' => 7, 'error_message' => 'Данные из блока Утверждения документации по планировке территории не могут быть заполнены при указанном Виде объекта']));
        }
        
        // Валидация Даты
        if($P_date_planning_documentation_approval !== '' && !$formHandler->validateDate($P_date_planning_documentation_approval)){
            
            exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение даты Утверждения документации по планировке территории']));
        }
        
    }elseif($P_number_GPZU !== '' || $P_date_GPZU !== ''){
        
        // Заполнены данные при невыбранном Виде объекта или Вид объекта не того типа
        if(!type_of_object_exist || $form_typeOfObjectID !== 2){
            
            exit(json_encode(['result' => 7, 'error_message' => 'Данные из блока ГПЗУ не могут быть заполнены при указанном Виде объекта']));
        }
        
        // Валидация Даты
        if($P_date_GPZU !== '' && !$formHandler->validateDate($P_date_GPZU)){
            
            exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение даты ГПЗУ']));
        }
    }
    
    
    // Проверка Вида работ ---------------------------------------------------------------------
    //
    if($P_type_of_work !== ''){
        
        if(!expertise_purpose_exist){
            exit(json_encode(['result' => 7, 'error_message' => 'Вид работ не может быть заполнен при невыбранной Цели обращения']));
        }
        
        $typeOfWorkValidateResult = $formHandler->validateDependentMisc($form_expertisePurposeID, $P_type_of_work, 'misc_typeOfWorkTable');
        
        if($typeOfWorkValidateResult['error']){
            
            switch($typeOfWorkValidateResult['error_code']){
                case 1: // Передано некорректное значение зависимого справочника
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Вида работ']));
                case 2: // Запрашиваемая в форме зависимость не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Вида работ не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_typeOfWorkID = $typeOfWorkValidateResult['int_formValueDependent'];
        
        define('type_of_work_exist', true);
    }else{
        
        define('type_of_work_exist', false);
    }
    
    
    // Проверка Типа объекта культурного наследия ----------------------------------------------
    //
    if($P_cultural_object_type !== ''){
        
        if($P_cultural_object_type_checkbox !== '1'){
            exit(json_encode(['result' => 7, 'error_message' => 'Тип объекта культурного наследия не может быть заполнен при невыбраном чекбоксе Объект культурного наследия (Да)']));
        }
        
        $culturalObjectTypeValidateResult = $formHandler->validateSingleMisc($P_cultural_object_type, 'misc_culturalObjectTypeTable');
        
        if($culturalObjectTypeValidateResult['error']){
            
            switch($culturalObjectTypeValidateResult['error_code']){
                case 1: // Передано некорректное значение справочника
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Типа объекта культурного наследия']));
                case 2: // Запрашиваемый справочник не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Типа объекта культурного наследия не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_culturalObjectTypeID = $culturalObjectTypeValidateResult['int_formValue'];
        
        define('cultural_object_type_exist', true);
    }else{
        
        define('cultural_object_type_exist', false);
    }
    
    
    // Проверка Национального проекта ----------------------------------------------------------
    //
    if($P_national_project !== ''){
        
        if($P_national_project_checkbox !== '1'){
            exit(json_encode(['result' => 7, 'error_message' => 'Название национального проекта не может быть заполнен при невыбраном чекбоксе Национальный проект (Да)']));
        }
        
        $nationalProjectValidateResult = $formHandler->validateSingleMisc($P_national_project, 'misc_nationalProjectTable');
        
        if($nationalProjectValidateResult['error']){
            
            switch($nationalProjectValidateResult['error_code']){
                case 1: // Передано некорректное значение справочника
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Национального проекта']));
                case 2: // Запрашиваемый справочник не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Национального проекта не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_nationalProjectID = $nationalProjectValidateResult['int_formValue'];
        
        define('national_project_exist', true);
    }else{
        
        define('national_project_exist', false);
    }
    
    
    // Проверка Федерального проекта -----------------------------------------------------------
    //
    if($P_federal_project !== ''){
        
        if(!national_project_exist){
            exit(json_encode(['result' => 7, 'error_message' => 'Федеральный проект не может быть заполнен при невыбранном Национальном проекте']));
        }
        
        $federalProjectValidateResult = $formHandler->validateDependentMisc($form_nationalProjectID, $P_federal_project, 'misc_federalProjectTable');
        
        if($federalProjectValidateResult['error']){
            
            switch($federalProjectValidateResult['error_code']){
                case 1: // Передано некорректное значение зависимого справочника
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Федерального проекта']));
                case 2: // Запрашиваемая в форме зависимость не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Федерального проекта не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_federalProjectID = $federalProjectValidateResult['int_formValueDependent'];
        
        define('federal_project_exist', true);
    }else{
        
        define('federal_project_exist', false);
    }
    
    
    // Проверка Даты окончания строительства -----------------------------------------------------------
    //
    if($P_date_finish_building !== ''){
        
        if($P_national_project_checkbox !== '1'){
            exit(json_encode(['result' => 7, 'error_message' => 'Дата окончания строительства не может быть заполнена при невыбраном чекбоксе Национальный проект (Да)']));
        }
        
        if(!$formHandler->validateDate($P_date_finish_building)){
            
            exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Даты окончания строительства']));
        }
    }
    
    // Проверка Куратора -------------------------------------------------------------------------------
    //
    if($P_curator !== ''){
        
        $curatorValidateResult = $formHandler->validateSingleMisc($P_curator, 'misc_curatorTable');
        
        if($curatorValidateResult['error']){
            
            switch($curatorValidateResult['error_code']){
                case 1: // Передано некорректное значение справочника
                    exit(json_encode(['result' => 4, 'error_message' => 'Передано некорректное значение Куратора']));
                case 2: // Запрашиваемый справочник не существует
                    exit(json_encode(['result' => 5, 'error_message' => 'Запрашиваемый справочник Куратора не существует']));
            }
        }
        
        // int'овое значение из формы
        $form_curatorID = $curatorValidateResult['int_formValue'];
        
        define('curator_exist', true);
    }else{
        
        define('curator_exist', false);
    }
    
    
    // -----------------------------------------------------------------------------------------------------------------
    // Зона сохранения заявления в БД
    // -----------------------------------------------------------------------------------------------------------------
    //
    // Имеет bool константы:
    //  1 - expertise_purpose_exist            : передана Цель обращения
    //  2 - expertise_subjects_exist           : передан(ы) Предметы экспертизы
    //  3 - type_of_object_exist               : передан Вид объекта
    //  4 - functional_purpose_exist           : передано Функциональное назначение
    //  5 - functional_purpose_subsector_exist : передано Функциональное назначение. Подотрасль
    //  6 - functional_purpose_group_exist     : передано Функциональное назначение. Группа
    //  7 - type_of_work_exist                 : передан Вид работ
    //  8 - cultural_object_type_exist         : передан Тип объекта культурного наследия
    //  9 - national_project_exist             : передан Национальный проект
    // 10 - federal_project_exist              : передан Федеральный проект
    // 11 - curator_exist                      : передан Куратор
    //
    // Если константы true, то определены:
    //  1 - form_expertisePurposeID           int : id выбранной Цели обращения
    //  2 - form_expertiseSubjects  array[int...] : массив с выбранными предметами (int) экспертизы
    //  3 - form_typeOfObjectID               int : id выбранного Вида объекта
    //  4 - form_functionalPurposeID          int : id выбранного Функционального назначение
    //  5 - form_functionalPurposeSubsectorID int : id выбранного Функциональное назначение. Подотрасль
    //  6 - form_functionalPurposeGroupID     int : id выбранного Функциональное назначение. Группа
    //  7 - form_typeOfWorkID                 int : id выбранного Вида работ
    //  8 - form_culturalObjectTypeID         int : id выбранного Типа объекта культурного наследия
    //  9 - form_nationalProjectID            int : id выбранного Национального проекта
    // 10 - form_federalProjectID             int : id выбранного Федерального проекта
    // 11 - form_curatorID                    int : id выбранного Куратора
    //
    // -----------------------------------------------------------------------------------------------------------------
    
    // Формируем ассоциативный массив данных, которые необходимо обновить в БД
    // ключ     - название столюца в БС
    // значение - новое значение, которое будет установлено
    $dataToUpdate = [];
    
    
    // Цель обращения (справочник, нельзя сбросить) --------------------------------------------
    if(expertise_purpose_exist && $form_expertisePurposeID !== $applicationAssoc['id_expertise_purpose']){
        $dataToUpdate['id_expertise_purpose'] = $form_expertisePurposeID;
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
    foreach($expertiseSubjectsToDelete as $id){
        ExpertiseSubjectTable::delete($form_applicationID, $id);
    }
    foreach($expertiseSubjectsToCreate as $id){
        ExpertiseSubjectTable::create($form_applicationID, $id);
    }
    
    
    // Дополнительная информация (текстовое поле) ----------------------------------------------
    $formHandler->addValueToUpdate($P_additional_information, 'additional_information', $dataToUpdate);
    
    
    // Наименование объекта (текстовое поле) ---------------------------------------------------
    $formHandler->addValueToUpdate($P_object_name, 'object_name', $dataToUpdate);
    
    
    // Вид объекта (справочник, нельзя сбросить) -----------------------------------------------
    if(type_of_object_exist && $form_typeOfObjectID !== $applicationAssoc['id_type_of_object']){
        $dataToUpdate['id_type_of_object'] = $form_typeOfObjectID;
    }
    
    
    // Функциональное назначение (справочник, нельзя сбросить) ----------------------------------
    if(functional_purpose_exist && $form_functionalPurposeID !== $applicationAssoc['id_functional_purpose']){
        $dataToUpdate['id_functional_purpose'] = $form_functionalPurposeID;
    }
    
    
    // Функциональное назначение. Подотрасль (справочник, можно сбросить) -----------------------
    $tmpFormValue = functional_purpose_subsector_exist ? $form_functionalPurposeSubsectorID : '';
    $formHandler->addValueToUpdate($tmpFormValue, 'id_functional_purpose_subsector', $dataToUpdate);
    
    
    // Функциональное назначение. Группа (справочник, можно сбросить) ---------------------------
    $tmpFormValue = functional_purpose_group_exist ? $form_functionalPurposeGroupID : '';
    $formHandler->addValueToUpdate($tmpFormValue, 'id_functional_purpose_group', $dataToUpdate);
    
    // Номер утверждения документации по планировке территории (текстовое поле) -----------------
    $formHandler->addValueToUpdate($P_number_planning_documentation_approval, 'number_planning_documentation_approval', $dataToUpdate);
    
    
    // Дата утверждения документации по планировке территории (текстовое поле, календарь) -------
    $formHandler->addValueToUpdate(strtotime($P_date_planning_documentation_approval), 'date_planning_documentation_approval', $dataToUpdate);
    
    
    // Номер ГПЗУ (текстовое поле) -------------------------------------------------------------
    $formHandler->addValueToUpdate($P_number_GPZU, 'number_GPZU', $dataToUpdate);
    
    
    // Дата ГПЗУ (текстовое поле, календарь) ---------------------------------------------------
    $formHandler->addValueToUpdate(strtotime($P_date_GPZU), 'date_GPZU', $dataToUpdate);
    
    
    // Вид работ (справочник, можно сбросить)
    $tmpFormValue = type_of_work_exist ? $form_typeOfWorkID : '';
    $formHandler->addValueToUpdate($tmpFormValue, 'id_type_of_work', $dataToUpdate);
    
    
    // Кадастровый номер земельного участка (текстовое поле) -----------------------------------
    $formHandler->addValueToUpdate($P_cadastral_number, 'cadastral_number', $dataToUpdate);
    
    
    // Тип объекта культурного наследия (справочник, можно сбросить) ---------------------------
    $tmpFormValue = cultural_object_type_exist ? $form_culturalObjectTypeID : '';
    $formHandler->addValueToUpdate($tmpFormValue, 'id_cultural_object_type', $dataToUpdate);
    
    
    // Национальный проект (справочник, можно сбросить) ----------------------------------------
    $tmpFormValue = national_project_exist ? $form_nationalProjectID : '';
    $formHandler->addValueToUpdate($tmpFormValue, 'id_national_project', $dataToUpdate);
    
    
    // Федеральный проект (справочник, можно сбросить) -----------------------------------------
    $tmpFormValue = federal_project_exist ? $form_federalProjectID : '';
    $formHandler->addValueToUpdate($tmpFormValue, 'id_federal_project', $dataToUpdate);
    
    
    // Дата окончания строительства (текстовое поле, календарь) --------------------------------
    $formHandler->addValueToUpdate(strtotime($P_date_finish_building), 'date_finish_building', $dataToUpdate);
    
    
    // Куратор (справочник, нельзя сбросить) ----------------------------------------------------
    if(curator_exist && $form_curatorID !== $applicationAssoc['id_curator']){
        $dataToUpdate['id_curator'] = $form_curatorID;
    }
    
    
    // Сохранение в БД полей заявления ---------------------------------------------------------
    //
    // Вызываем умное сохранение, если данные в заявлении поменялись
    if(!empty($dataToUpdate)){
        
        // Обновляем флаг сохраненности заявления для новых заявлений
        if(!$applicationAssoc['is_saved']){
            $dataToUpdate['is_saved'] = 1;
        }
        ApplicationsTable::smartUpdateById($dataToUpdate, $form_applicationID);
    }
    
    // Успешное сохранение
    exit(json_encode(['result' => 8]));
    
// Непредвиденная ошибка
}catch(Exception $e){
    
    exit(json_encode(['result'  => 9,
                      'message' => $e->getMessage(),
                      'code'	=> $e->getCode()
    ]));
}