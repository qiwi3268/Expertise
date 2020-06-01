<?php


// Класс предназначен для обработки форм
//
class ApplicationFormHandler{


    //private array $formData = [];
    private array $applicationAssoc;

    public function __construct(array $applicationAssoc){

        //$this->formData = $formData;
        $this->applicationAssoc = $applicationAssoc;
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

}
