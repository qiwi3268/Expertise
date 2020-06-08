<?php


// Класс предназначен для обработки форм
//
// Exception code:
// 1 - Не существует переданный класс
// 2 - Переданный класс не реализует требуемый интерфейс

class ApplicationFormHandler{



    private array $applicationAssoc;

    public function __construct(array $applicationAssoc){

        $this->applicationAssoc = $applicationAssoc;
    }



    // -----------------------------------------------------------------------------------------
    // Зона валидации текстовых полей
    // -----------------------------------------------------------------------------------------


    // error_code 1 - ошибка при парсинге json'а
    //      exception_message
    //      exception_code
    // error_code 2 - в массиве присутствуют нечисловые элементы
    // error_code 3 - в массиве присутствуют одинаковые элементы
    public function validateNumericalJson(string $formValue, bool $checkSame = true):array {

        try{

            $array = json_decode($formValue, false, 2, JSON_THROW_ON_ERROR);
        }catch(jsonException $ex){

            return ['error'             => true,
                    'error_code'        => 1,
                    'exception_message' => $ex->getMessage(),
                    'exception_code'    => $ex->getCode()
            ];
        }

        // Проверка массива на числовые значения
        foreach($array as &$element){

            // Явное преобразование к типу int
            $int_element = (int)$element;

            // При преобразовнии обрезались символы
            // Достаточно гибкого сравнения, т.к. сравниваются строки
            if((string)$int_element != $element){

                return ['error'      => true,
                        'error_code' => 2
                ];
            }

            $element = $int_element;
        }
        unset($element);

        // Проверка массива на одинаковые значения
        if($checkSame){

            foreach($array as $element){

                // Количество таких-же элементов в массиве, как текущий (включая его)
                $sameCount = count(array_keys($array, $element, true));

                if($sameCount > 1){
                    return ['error'      => true,
                            'error_code' => 3,
                    ];
                }
            }
        }

        return ['error'         => false,
                'int_formArray' => $array
        ];
    }

    public function validateDate(string $formValue):bool {

        $dateParts = explode('.', $formValue, 3);

        if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[0], $dateParts[2])){
            return false;
        }
        return true;
    }



    // -----------------------------------------------------------------------------------------
    // Зона добавления данных к dataToUpdate массиву
    // -----------------------------------------------------------------------------------------



    // Предназначен для добавления нового значения formValue для столбца columnName
    // в общий список данных, которым нужен update
    // Принимает параметры-----------------------------------
    // formValue     string : значение из переданной формы (всегда строка)
    // columnName    string : имя столбца в БД, оно же имя ключа в ассоциативном массиве
    // & dataToUpdate array : ссылка на массив для сохранения данных, которым нужно сделать update
    //
    public function addValueToUpdate(string $formValue, string $columnName, array &$dataToUpdate):void {

        // Из формы пришло пустое значение
        if($formValue === ''){

            // В БД было что-то записано (пользователь удалил информацию)
            if(!is_null($this->applicationAssoc[$columnName])){
                $dataToUpdate[$columnName] = NULL;
            }
        // Из формы пришло значение
        }else{

            // Если поле в БД представлено числом (например, дата или справочник), то необходимо преобразовать его
            // к int'у, т.к. далее следует жесткое сравнение
            if(is_int($this->applicationAssoc[$columnName])){
                $formValue = (int)$formValue;
            }

            // Пользователь отправил данные, отличающиеся от записи в БД
            // Жесткое сравнение необходимо, чтобы отличать введенный 0 и NULL из БД и т.д.
            if($formValue !== $this->applicationAssoc[$columnName]){
                $dataToUpdate[$columnName] = $formValue;
            }
        }

        // Пользователь отправил пустое значение, а БД - NULL : не обновляем данные
        // Пользователь отправил значение, такое же, как в БД : не обновляем данные
    }



    // -----------------------------------------------------------------------------------------
    // Зона валидации справочников
    // -----------------------------------------------------------------------------------------



    // Предназначен для валидации независимого справочника, который может выбирать только один элемент
    // * Справочник должен реализовывать интерфейс Interface_singleMiscTableValidate
    // Принимает параметры-----------------------------------
    // formValue string : значение из переданной формы (всегда строка)
    // ClassName string : название класса справочника
    // Возвращает параметры----------------------------------
    // array => 'error' bool : true  - есть ошибка при проверке
    //                         false - нет ошибок
    //       (если есть ошибка)
    //       => 'error_code' int : 1 - передано некорректное значение справочника
    //                             2 - запрашиваемый справочник не существует
    //       (если нет ошибки)
    //       => 'int_formValue' int : преобразованный к int'у входной параметр
    public function validateSingleMisc(string $formValue, string $ClassName):array {

        // Явное преобразование к типу int
        $int_formValue = (int)$formValue;

        // При преобразовнии обрезались символы
        // Достаточно гибкого сравнения, т.к. сравниваются строки
        if((string)$int_formValue != $formValue){

            return ['error'      => true,
                    'error_code' => 1
            ];
        }

        // Проверка на существование указанного класса
        if(!class_exists($ClassName)){
            throw new ApplicationFormHandlerException("Класс $ClassName не существует");
        }

        $interfaces = class_implements($ClassName);

        // Проверка на реализацию интерфейса Interface_singleMiscTableValidate в нужном классе
        if(!$interfaces || !in_array('Interface_singleMiscTableValidate', $interfaces, true)){
            throw new ApplicationFormHandlerException("Класс $ClassName не реализует интерфейс Interface_singleMiscTableValidate");
        }

        $isExist = call_user_func([$ClassName, 'checkExistById'], $int_formValue);

        // Запрашиваемый в форме справочник не существует
        if(!$isExist){

            return ['error'      => true,
                    'error_code' => 2
            ];
        }

        // Все проверки прошли успешно
        return ['error'         => false,
                'int_formValue' => $int_formValue
        ];
    }


    // Предназначен для валидации зависимого справочника, который может выбирать только один элемент
    // валидация заключается в проверке существования зависимости главного и зависимого справочника
    // * Справочник должен реализовывать интерфейс Interface_dependentMiscTableValidate
    // Принимает параметры-----------------------------------
    // int_formValueMain     int : валидное значение из переданной формы (всегда строка)
    // * Предполагается, что главный справочник уже прошел валидацию
    // formValueDependent string : значение зависимого справочника из переданной формы (всегда строка)
    // ClassName          string : название класса зависимого справочника
    // Возвращает параметры----------------------------------
    // array => 'error'                  bool : true  - есть ошибка при проверке
    //                                          false - нет ошибок
    //       (если есть ошибка)
    //       => 'error_code'             int : 1 - передано некорректное значение зависимого справочника
    //                                         2 - запрашиваемая в форме зависимость не существует
    //       (если нет ошибки)
    //       => 'int_formValueDependent' int : преобразованный к int'у входной параметр зависимого справочника
    //
    public function validateDependentMisc(int $int_formValueMain,string $formValueDependent, string $ClassName):array {

        // Явное преобразование к типу int
        $int_formValueDependent = (int)$formValueDependent;

        // При преобразовнии обрезались символы
        // Достаточно гибкого сравнения, т.к. сравниваются строки
        if((string)$int_formValueDependent != $formValueDependent){

            return ['error'      => true,
                    'error_code' => 1
            ];
        }

        // Проверка на существование указанного класса
        if(!class_exists($ClassName)){
            throw new ApplicationFormHandlerException("Класс $ClassName не существует");
        }

        $interfaces = class_implements($ClassName);

        // Проверка на реализацию интерфейса Interface_singleMiscTableValidate в нужном классе
        if(!$interfaces || !in_array('Interface_dependentMiscTableValidate', $interfaces, true)){
            throw new ApplicationFormHandlerException("Класс $ClassName не реализует интерфейс Interface_dependentMiscTableValidate");
        }

        $isExist = call_user_func([$ClassName, 'checkExistCORRByIds'], $int_formValueMain, $int_formValueDependent);

        // Запрашиваемая в форме зависимость не существует
        if(!$isExist){

            return ['error'      => true,
                    'error_code' => 2
            ];
        }

        // Все проверки прошли успешно
        return ['error'                  => false,
                'int_formValueDependent' => $int_formValueDependent
        ];
    }
}
