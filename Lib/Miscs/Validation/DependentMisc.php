<?php


namespace Lib\Miscs\Validation;

use Lib\Exceptions\MiscValidator as SelfException;


// Предназначен для валидации зависимых справочников
// *** При валидации зависимого справочника предполагается, что главный справочник был провалидирован ранее
class DependentMisc extends Validator
{
    private const INTERFACE = 'Tables\Miscs\Interfaces\DependentMiscValidate';
    private const METHOD = 'checkExistCorrByIds';


    private Validator $MainValidator; // Объект главного справочника


    // Принимает параметры-----------------------------------
    // MainValidator Validator : полученный ранее объект валидатора главного справочника
    // form_value       string : значение из формы
    // class            string : название класса справочника
    public function __construct(Validator $MainValidator, string $form_value, string $class)
    {
        $this->MainValidator = $MainValidator;
        $this->form_value = $form_value;
        $this->class = $class;
    }


    // Предназначен для комплексной проверки справочника
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\MiscValidator :
    // code:
    //  5 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
    //
    public function validate()
    {
        if ($this->form_value !== '') {

            if (!$this->MainValidator->isExist()) {
                throw new SelfException("При наличии значения зависимого справочника: '{$this->class}', флаг наличия проверенных данных главного справочника отрицательный", 5);
            }

            $int = $this->int_value = $this->getValidatedInt($this->form_value);

            $this->checkClass($this->class, self::INTERFACE);

            $this->checkMiscExist($this->class, self::METHOD, [$this->MainValidator->getIntValue(), $int]);

            $this->isExist = true;
        } else {

            $this->isExist = false;
        }
    }
}
