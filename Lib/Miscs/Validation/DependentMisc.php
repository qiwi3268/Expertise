<?php


namespace Lib\Miscs\Validation;

use Lib\Exceptions\MiscValidator as SelfEx;


/**
 * Предназначен для валидации зависимых справочников
 *
 * <b>***</b> При валидации зависимого справочника предполагается, что главный справочник был провалидирован ранее
 */
class DependentMisc extends Validator
{

    private const INTERFACE = 'Tables\Miscs\Interfaces\DependentMiscValidate';
    private const METHOD = 'checkExistCorrByIds';

    /**
     * Объект главного справочника
     *
     */
    private Validator $MainValidator;


    /**
     * Конструктор класса
     *
     * @param Validator $MainValidator полученный ранее объект валидатора главного справочника
     * @param string $form_value значение из формы
     * @param string $class название класса справочника
     */
    public function __construct(Validator $MainValidator, string $form_value, string $class)
    {
        $this->MainValidator = $MainValidator;
        $this->form_value = $form_value;
        $this->class = $class;
    }


    /**
     * Предназначен для комплексной проверки справочника
     *
     * <b>*</b> Возвращаемый тип не объявлен, чтобы дочерние классы при желании могли реализовать цепочки вызово
     *
     * @throws SelfEx
     */
    public function validate()
    {
        if ($this->form_value !== '') {

            if (!$this->MainValidator->isExist()) {
                throw new SelfEx("При наличии значения зависимого справочника: '{$this->class}', флаг наличия проверенных данных главного справочника отрицательный", 5);
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
