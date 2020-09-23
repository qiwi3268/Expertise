<?php


use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Exceptions\MiscValidator as MiscValidatorEx;
use Classes\Exceptions\ApplicationFormMiscValidator as ApplicationFormMiscValidatorEx;

use core\Classes\Session;
use Lib\DataBase\Transaction;
use Lib\Singles\PrimitiveValidator;
use Classes\Application\DataToUpdate;
use Classes\Application\Miscs\Validation\SingleMisc as SingleMiscValidator;
use Classes\Application\Miscs\Validation\DependentMisc as DependentMiscValidator;
use Tables\expertise_subject;
use Tables\Docs\application;


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
//---------------------------------------------
// finance_sources_1 - Технические ошибки при валидации источников финансирования (со сторны клиенского js)
//                     {result, message : текст ошибки, code: код ошибки}
// finance_sources_2 - Ошибки со стороны пользователя (введены некорректные данные)
//                     {result, message : текст ошибки, code: код ошибки}

if (!checkParamsPOST(
    'id_application',
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
    'estimate_cost',
    'cadastral_number',
    'cultural_object_type_checkbox',
    'cultural_object_type',
    'national_project_checkbox',
    'national_project',
    'federal_project',
    'date_finish_building',
    'curator',
    'finance_sources'
)) {
    exit(json_encode([
        'result'        => 1,
        'error_message' => 'Нет обязательных параметров POST запроса'
    ]));
}


try {

    /** @var string $P_id_application id-заявления */
    /** @var string $P_expertise_purpose Цель обращения */
    /** @var string $P_expertise_subjects Предмет(ы) экспертизы JSON */
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
    /** @var string $P_estimate_cost Сведения о сметной или предполагаемой (предельной) стоимости объекта */
    /** @var string $P_cadastral_number Кадастровый номер земельного участка */
    /** @var string $P_cultural_object_type_checkbox Тип объекта культурного наследия (ЧЕКБОКС) */
    /** @var string $P_cultural_object_type Тип объекта культурного наследия */
    /** @var string $P_national_project_checkbox Национальный проект (ЧЕКБОКС) */
    /** @var string $P_national_project Национальный проект */
    /** @var string $P_federal_project Федеральный проект */
    /** @var string $P_date_finish_building Дата окончания строительства */
    /** @var string $P_curator Куратор */
    /** @var string $P_finance_sources_exist_flag Флаг переданных источников финансирования */
    /** @var string $P_finance_sources Истичник(и) финансирования JSON */
    extract(clearHtmlArr($_POST), EXTR_PREFIX_ALL, 'P');


    // -----------------------------------------------------------------------------------------------------------------
    // Зона валидации формы
    // -----------------------------------------------------------------------------------------------------------------

    // Преобразуем значение из формы явно к типу int
    $form_applicationID = (int)$P_id_application;

    // Проверка на доступ к заявлению и его существование
    $applicationAssoc = application::getFlatAssocById($form_applicationID);

    $applicationExist = !is_null($applicationAssoc);

    if (Session::isApplicant()) {

        // Проверка прав заявителя на доступ к сохранению заявления
        // todo переделать на вызов из специализированного класса
        //$canSaveApplication = \Classes\Application\Helpers\Helper::checkApplicantRightsToSaveApplication($form_applicationID);

        $canSaveApplication = true;

        // Заявителю говорим, что нет прав, даже в том случае, если заявление не существует,
        // чтобы не могли через html проверять заявления
        if (!$applicationExist || !$canSaveApplication) {

            exit(json_encode([
                'result'        => 2,
                'error_message' => "У заявителя отсуствуют права на сохранение заявления id: {$form_applicationID}"
            ]));
        }


    } else {
        // Сотрудник
        if (!$applicationExist) {

            exit(json_encode([
                'result'        => 3,
                'error_message' => "Заявление id: {$form_applicationID} не существует"
            ]));
        }
    }

    $transaction = new Transaction();
    $primitiveValidator = new PrimitiveValidator();
    DataToUpdate::setFlatAssoc($applicationAssoc);


    try {

        // Проверка цели обращения (и добавление к массиву обновлений) -----------------------------
        $expertisePurpose = new SingleMiscValidator($P_expertise_purpose, '\Tables\Miscs\expertise_purpose', 'id_expertise_purpose');
        $expertisePurpose->validate()->addToUpdate();


        // Проверка предметов экспертизы -----------------------------------------------------------
        if ($P_expertise_subjects != '') {
            $expertiseSubjects = $primitiveValidator->getValidatedArrayFromNumericalJson($P_expertise_subjects, true);
            foreach ($expertiseSubjects as $id) (new DependentMiscValidator($expertisePurpose, $id, '\Tables\Miscs\expertise_subject'))->validate();
        }


        // Проверка вида объекта (и добавление к массиву обновлений) -------------------------------
        $typeOfObject = new SingleMiscValidator($P_type_of_object, '\Tables\Miscs\type_of_object', 'id_type_of_object');
        $typeOfObject->validate()->addToUpdate();


        // Проверка функционального назначения (и добавление к массиву обновлений) -----------------
        $functionalPurpose = new SingleMiscValidator($P_functional_purpose, '\Tables\Miscs\functional_purpose', 'id_functional_purpose');
        $functionalPurpose->validate()->addToUpdate();


        // Проверка функциональное назначение. Подотрасль (и добавление к массиву обновлений) ------
        $functionalPurposeSubsector = new DependentMiscValidator($functionalPurpose, $P_functional_purpose_subsector, '\Tables\Miscs\functional_purpose_subsector', 'id_functional_purpose_subsector');
        $functionalPurposeSubsector->validate()->addToUpdate();


        // Проверка функциональное назначение. Группа (и добавление к массиву обновлений) ----------
        $functionalPurposeGroup = new DependentMiscValidator($functionalPurposeSubsector, $P_functional_purpose_group, '\Tables\Miscs\functional_purpose_group', 'id_functional_purpose_group');
        $functionalPurposeGroup->validate()->addToUpdate();


        // Проверка вида работ (и добавление к массиву обновлений) ---------------------------------
        $typeOfWork = new DependentMiscValidator($expertisePurpose, $P_type_of_work, '\Tables\Miscs\type_of_work', 'id_type_of_work');
        $typeOfWork->validate()->addToUpdate();


        // Проверка типа объекта культурного наследия (и добавление к массиву обновлений) ----------
        if ($P_cultural_object_type != '' && $P_cultural_object_type_checkbox != '1') {
            exit(json_encode([
                'result'        => 7,
                'error_message' => 'Тип объекта культурного наследия не может быть заполнен при невыбраном чекбоксе Объект культурного наследия (Да)'
            ]));
        }
        $culturalObjectType = new SingleMiscValidator($P_cultural_object_type, '\Tables\Miscs\cultural_object_type', 'id_cultural_object_type');
        $culturalObjectType->validate()->addToUpdate();


        // Проверка национального проекта (и добавление к массиву обновлений) ----------------------
        if ($P_national_project != '' && $P_national_project_checkbox != '1') {
            exit(json_encode([
                'result'        => 7,
                'error_message' => 'Название национального проекта не может быть заполнено при невыбраном чекбоксе Национальный проект (Да)'
            ]));
        }
        $nationalProject = new SingleMiscValidator($P_national_project, '\Tables\Miscs\national_project', 'id_national_project');
        $nationalProject->validate()->addToUpdate();


        // Проверка федерального проекта (и добавление к массиву обновлений) -----------------------
        $federalProject = new DependentMiscValidator($nationalProject, $P_federal_project, '\Tables\Miscs\federal_project', 'id_federal_project');
        $federalProject->validate()->addToUpdate();


        // Проверка куратора (и добавление к массиву обновлений) -----------------------------------
        $curator = new SingleMiscValidator($P_curator, '\Tables\Miscs\curator', 'id_curator');
        $curator->validate()->addToUpdate();


    } catch (ApplicationFormMiscValidatorEx $e) {
        //  4 - передано некорректное значение справочника
        //  5 - запрашиваемое значение справочника не существует
        //  7 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
        exit(json_encode([
            'result'        => $e->getCode(),
            'error_message' => $e->getMessage()
        ]));
    } catch (PrimitiveValidatorEx $e) {
        // (валидация предметов экспертизы)
        // result 4
        exit(json_encode([
            'result'        => 4,
            'error_message' => $e->getMessage()
        ]));
    }


    // Проверка блока Номера и Даты ------------------------------------------------------------
    //
    // Из формы одновременное пришли данные из блока утверждения документации по планировке территории и ГПЗУ
    if (
        ($P_number_planning_documentation_approval != '' || $P_date_planning_documentation_approval != '')
        && ($P_number_GPZU != '' || $P_date_GPZU != '')
    ) {

        exit(json_encode([
            'result'        => 6,
            'error_message' => 'Одновременно переданы данные из блока Утверждения документации по планировке территории и ГПЗУ'
        ]));
    }

    if ($P_number_planning_documentation_approval != '' || $P_date_planning_documentation_approval != '') {

        // Заполнены данные при невыбранном виде объекта или вид объекта не того типа
        if (!$typeOfObject->isExist() || $typeOfObject->getIntValue() != 1) {

            exit(json_encode([
                'result'        => 7,
                'error_message' => 'Данные из блока Утверждения документации по планировке территории не могут быть заполнены при указанном Виде объекта'
            ]));
        }

        // Валидация даты
        try {
            if ($P_date_planning_documentation_approval != '') $primitiveValidator->validateStringDate($P_date_planning_documentation_approval);
        } catch (PrimitiveValidatorEx $e) {

            exit(json_encode([
                'result'        => 4,
                'error_message' => 'Передано некорректное значение даты Утверждения документации по планировке территории'
            ]));
        }

    } elseif ($P_number_GPZU != '' || $P_date_GPZU != '') {

        // Заполнены данные при невыбранном виде объекта или вид объекта не того типа
        if (!$typeOfObject->isExist() || !$typeOfObject->getIntValue() != 2) {

            exit(json_encode([
                'result'        => 7,
                'error_message' => 'Данные из блока ГПЗУ не могут быть заполнены при указанном Виде объекта'
            ]));
        }

        // Валидация даты
        try {
            if ($P_date_GPZU != '') $primitiveValidator->validateStringDate($P_date_GPZU);
        } catch (PrimitiveValidatorEx $e) {

            exit(json_encode([
                'result'        => 4,
                'error_message' => 'Передано некорректное значение даты ГПЗУ'
            ]));
        }
    }

    // Проверка сведений о сметной или предполагаемой (предельной) стоимости объекта -------------------
    if ($P_estimate_cost != '') {

        // Предмет экспертизы должен включать в себя: Проверка достоверности определения сметной стоимости...
        if ($P_expertise_subjects === '' || !in_array(3, $expertiseSubjects, true)) {

            exit(json_encode([
                'result'        => 7,
                'error_message' => 'Сведения о сметной или предполагаемой (предельной) стоимости объекта не могут быть заполнены при невыбранном предмете экспертизы: "Проверка достоверности определения сметной стоимости..."'
            ]));
        }

        try {
            $primitiveValidator->validateInt($P_estimate_cost);
        } catch (PrimitiveValidatorEx $e) {
            exit(json_encode([
                'result'        => 4,
                'error_message' => 'Передано некорректное значение сведений о сметной или предполагаемой (предельной) стоимости объекта'
            ]));
        }
    }


    // Проверка даты окончания строительства -----------------------------------------------------------
    //
    if ($P_date_finish_building != '') {

        if ($P_national_project_checkbox != '1') {

            exit(json_encode([
                'result'        => 7,
                'error_message' => 'Дата окончания строительства не может быть заполнена при невыбраном чекбоксе Национальный проект (Да)'
            ]));
        }

        try {
            $primitiveValidator->validateStringDate($P_date_finish_building);
        } catch (PrimitiveValidatorEx $e) {

            exit(json_encode([
                'result'        => 4,
                'error_message' => 'Передано некорректное значение Даты окончания строительства'
            ]));
        }
    }


    // Проверка источников финансирования --------------------------------------------------------------
    //
    // В источники финансирования было внесено изменение
    if ($P_finance_sources_exist_flag == '1') {

        try {

            // Получаем массив из входного json'а
            $financingSources = $primitiveValidator->getAssocArrayFromJson($P_finance_sources);

            // Проверяем структуру массива и валидируем его
            foreach ($financingSources as $source) {

                $primitiveValidator->validateSomeInclusions($source['type'], '1', '2', '3', '4');

                switch ($source['type']) {
                    case '1' :

                        $budgetLevel = new SingleMiscValidator((is_null($source['budget_level']) ? '' : $source['budget_level']), '\Tables\Miscs\budget_level');

                        $settings = [
                            'budget_level' => ['is_null', [$budgetLevel, 'validate']],
                            'no_data'      => [[$primitiveValidator, 'validateSomeInclusions', null, '1']],
                            'percent'      => ['is_null', [$primitiveValidator, 'validatePercent']]
                        ];
                        break;

                    case '2' :

                        $settings = [
                            'full_name' => ['is_null', 'is_string'],
                            'INN'       => ['is_null', [$primitiveValidator, 'validateINN']],
                            'KPP'       => ['is_null', [$primitiveValidator, 'validateKPP']],
                            'OGRN'      => ['is_null', [$primitiveValidator, 'validateOGRN']],
                            'address'   => ['is_null', 'is_string'],
                            'location'  => ['is_null', 'is_string'],
                            'telephone' => ['is_null', 'is_string'],
                            'email'     => ['is_null', [$primitiveValidator, 'validateEmail']],
                            'no_data'   => [[$primitiveValidator, 'validateSomeInclusions', null, '1']],
                            'percent'   => ['is_null', [$primitiveValidator, 'validatePercent']]
                        ];
                        break;

                    case '3' :
                    case '4' :

                        $settings = [
                            'no_data' => [[$primitiveValidator, 'validateSomeInclusions', null, '1']],
                            'percent' => ['is_null', [$primitiveValidator, 'validatePercent']]
                        ];
                        break;
                }

                $primitiveValidator->validateAssociativeArray($source, $settings);
            }
        } catch (PrimitiveValidatorEx $e) {

            $message = $e->getMessage();
            $code = $e->getCode();

            switch ($code) {
                // Технические ошибки (со сторны клиенского js)
                case 1 :  // ошибка при декодировании json-строки
                case 13 : // во входном массиве отсутствует обязательное поле
                case 14 : // значение входного массива по ключу не прошло проверку
                case 15 : // значение не подходит ни под одно из перечисленных
                    exit(json_encode([
                        'result'  => 'finance_sources_1',
                        'message' => $message,
                        'code'    => $code
                    ]));

                // Ошибки со стороны пользователя (введены некорректные данные)
                case 7 :  // введенный ИНН является некорректным
                case 8 :  // введенный КПП является некорректным
                case 9 :  // введенный ОГРН является некорректным
                case 10 : // введенный email является некорректным
                case 11 : // введенный процент является некорректным
                    exit(json_encode([
                        'result'  => 'finance_sources_2',
                        'message' => $message,
                        'code'    => $code
                    ]));

                default :
                    throw new PrimitiveValidatorEx($message, $code);
            }

        } catch (ApplicationFormMiscValidatorEx $e) {

            //  4 - передано некорректное значение справочника
            //  5 - запрашиваемое значение справочника не существует
            exit(json_encode([
                'result'        => $e->getCode(),
                'error_message' => $e->getMessage()
            ]));
        }

        // Удаляем все источники финансирования, относящиеся к этому заявлению
        $transaction->add('\Tables\FinancingSources\type_1', 'deleteAllByIdApplication', [$form_applicationID]);
        $transaction->add('\Tables\FinancingSources\type_2', 'deleteAllByIdApplication', [$form_applicationID]);
        $transaction->add('\Tables\FinancingSources\type_3', 'deleteAllByIdApplication', [$form_applicationID]);
        $transaction->add('\Tables\FinancingSources\type_4', 'deleteAllByIdApplication', [$form_applicationID]);

        foreach ($financingSources as $source) {

            $no_data = is_null($source['no_data']) ? 0 : 1;

            switch ($source['type']) {

                case '1' :
                    $transaction->add('\Tables\FinancingSources\type_1', 'create', [
                        $form_applicationID,
                        $source['budget_level'],
                        $no_data,
                        $source['percent']
                    ]);
                    break;

                case '2' :
                    $transaction->add('\Tables\FinancingSources\type_2', 'create', [
                        $form_applicationID,
                        $source['full_name'],
                        $source['INN'],
                        $source['KPP'],
                        $source['OGRN'],
                        $source['address'],
                        $source['location'],
                        $source['telephone'],
                        $source['email'],
                        $no_data,
                        $source['percent']]
                    );
                    break;

                case '3' :
                    $transaction->add('\Tables\FinancingSources\type_3', 'create', [
                        $form_applicationID,
                        $no_data,
                        $source['percent']]
                    );
                    break;

                case '4' :
                    $transaction->add('\Tables\FinancingSources\type_4', 'create', [
                        $form_applicationID,
                        $no_data,
                        $source['percent']]
                    );
                    break;
            }
        }
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Зона сохранения заявления в БД
    // -----------------------------------------------------------------------------------------------------------------
    //

    // Предмет экспертизы (радио, можно сбросить) ----------------------------------------------

    // Предметы экспертизы, которые уже есть у заявления

    $db_expertiseSubjects = expertise_subject::getIdsByIdApplication($form_applicationID);

    $db_expertiseSubjects ??= [];    // Если с БД пришел null, то приравниваем к пустому массиву для array_diff
    $expertiseSubjectsToDelete = []; // Массив с id предметов экспертизы, которые нужно удалить
    $expertiseSubjectsToCreate = []; // Массив с id предметов экспертизы, которые нужно создать к заявлению

    if ($P_expertise_subjects != '') {

        // id предметов, которые есть в БД, но нет в пришедшей форме
        $expertiseSubjectsToDelete = array_diff($db_expertiseSubjects, $expertiseSubjects);

        // id предметов, которые есть в пришедшей форме, но нет в БД
        $expertiseSubjectsToCreate = array_diff($expertiseSubjects, $db_expertiseSubjects);

        // Из формы пришло пустое значение, удаляем все предметы экспертизы
    } else {
        $expertiseSubjectsToDelete = $db_expertiseSubjects;
    }

    // Удаляем и записываем в БД новые записи о предмете экспертизы
    foreach ($expertiseSubjectsToDelete as $id) {
        $transaction->add('\Tables\expertise_subject', 'delete', [$form_applicationID, $id]);
    }
    foreach ($expertiseSubjectsToCreate as $id) {
        $transaction->add('\Tables\expertise_subject', 'create', [$form_applicationID, $id]);
    }


    // Дополнительная информация (текстовое поле) ----------------------------------------------
    DataToUpdate::addString($P_additional_information, 'additional_information');

    // Наименование объекта (текстовое поле) ---------------------------------------------------
    DataToUpdate::addString($P_object_name, 'object_name');

    // Номер утверждения документации по планировке территории (текстовое поле) ----------------
    DataToUpdate::addString($P_number_planning_documentation_approval, 'number_planning_documentation_approval');

    // Дата утверждения документации по планировке территории (текстовое поле, календарь) ------
    DataToUpdate::addInt(strtotime($P_date_planning_documentation_approval), 'date_planning_documentation_approval');

    // Номер ГПЗУ (текстовое поле) -------------------------------------------------------------
    DataToUpdate::addString($P_number_GPZU, 'number_GPZU');

    // Дата ГПЗУ (текстовое поле, календарь) ---------------------------------------------------
    DataToUpdate::addInt(strtotime($P_date_GPZU), 'date_GPZU');

    // Сведения о сметной или предполагаемой (предельной) стоимости объекта --------------------
    DataToUpdate::addInt($P_estimate_cost, 'estimate_cost');

    // Кадастровый номер земельного участка (текстовое поле) -----------------------------------
    DataToUpdate::addString($P_cadastral_number, 'cadastral_number');

    // Дата окончания строительства (текстовое поле, календарь) --------------------------------
    DataToUpdate::addInt(strtotime($P_date_finish_building), 'date_finish_building');


    // Сохранение в БД полей заявления ---------------------------------------------------------
    //
    // Вызываем умное сохранение, если данные в заявлении поменялись
    if (!DataToUpdate::isEmpty()) {

        // Обновляем флаг сохраненности заявления для новых заявлений
        //todo проверить эту ветку
        if ($applicationAssoc['is_saved'] == 0) {
            DataToUpdate::addInt(1, 'is_saved');
        }

        $transaction->add(
            '\Tables\Docs\application',
            'smartUpdateById',
            [DataToUpdate::get(), $form_applicationID]
        );
    }

    //todo обернуть все в DataBaseEx?
    $transaction->start();

    // Успешное сохранение
    exit(json_encode(['result' => 8]));

// Ошибка валидации справочника
} catch (MiscValidatorEx $e) {

    // Логирование
    exit(json_encode([
        'result'  => 'todo',
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));

// Непредвиденная ошибка
} catch (Exception $e) {

    exit(json_encode([
        'result'  => 9,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ]));
}