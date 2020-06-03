<?php


// Класс предназначен для обработки форм
//
// Exception code:
// 1 - Не существует переданный класс
// 2 - Переданный класс не реализует требуемый интерфейс

class ApplicationFormHandler{



    private array $applicationAssoc;
    private array $formData;

    private string $application_id;


    public function __construct(array $applicationAssoc, array $formData){

        $this->applicationAssoc = $applicationAssoc;
        $this->formData = $formData;

        $this->application_id = $formData[_PROPERTY_IN_APPLICATION['application_id']];

    }










    public function validateJson(string $inputName){

    }

    // Предназначен для добавления нового значения formValue для столбца columnName
    // в общий список данных, которым нужен update
    // Принимает параметры-----------------------------------
    // formValue     string : значение из переданной формы (всегда строка)
    // columnName    string : имя столбца в БД, оно же имя ключа в ассоциативном массиве
    // & dataToUpdate array : ссылка на массив для сохранения данных, которым нужно сделать update
    //
    public function addTextInputValueToUpdate(string $formValue, string $columnName, array &$dataToUpdate):void {

        // Из формы пришло пустое значение
        if($formValue === ''){

            // В БД было что-то записано (пользователь удалил информацию)
            if(!is_null($this->applicationAssoc[$columnName])){
                $dataToUpdate[$columnName] = NULL;
            }
        // Из формы пришло значение
        }else{

            // Пользователь отправил данные, отличающиеся от записи в БД
            if($formValue !== $this->applicationAssoc[$columnName]){
                $dataToUpdate[$columnName] = $formValue;
            }
        }
        // Пользователь отправил пустое значение, а БД - NULL : не обновляем данные
        // Пользователь отправил значение, такое же, как в БД : не обновляем данные
    }


    // Предназначен для валидации справочника, который может выбирать только один элемент
    // Принимает параметры-----------------------------------
    // formValue string : значение из переданной формы (всегда строка)
    // ClassName string : название класса справочникаа
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
        if((string)$int_formValue != $formValue){

            return ['error'      => true,
                    'error_code' => 1
            ];
        }

        // Проверка на существование указанного в маппинге класса
        if(!class_exists($ClassName)){
            throw new ApplicationFormHandlerException("Класс $ClassName не существует", 1);
        }

        $interfaces = class_implements($ClassName);

        // Проверка на реализацию интерфейса Interface_miscTableValidate в нужном классе
        if(!$interfaces || !in_array('Interface_miscTableValidate', $interfaces, true)){
            throw new ApplicationFormHandlerException("Класс $ClassName не реализует интерфейс Interface_miscTableValidate", 2);
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

}
