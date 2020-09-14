<?php


namespace Lib\Miscs\Validation;

use Lib\Exceptions\MiscValidator as SelfEx;


/**
 * Предназначен для валидации одиночных справочников
 *
 */
class SingleMisc extends Validator
{
    private const INTERFACE = 'Tables\Miscs\Interfaces\SingleMiscValidate';
    private const METHOD = 'checkExistById';


    /**
     * Конструктор класса
     *
     * @param string $form_value значение из формы
     * @param string $class название класса справочника
     */
    public function __construct(string $form_value, string $class)
    {
        $this->form_value = $form_value;
        $this->class = $class;
    }


    /**
     * Предназначен для комплексной проверки справочника
     *
     * @throws SelfEx
     */
    public function validate(): void
    {
        if ($this->form_value !== '') {

            $int = $this->int_value = $this->getValidatedInt($this->form_value);

            $this->checkClass($this->class, self::INTERFACE);

            $this->checkMiscExist($this->class, self::METHOD, [$int]);

            $this->isExist = true;
        } else {

            $this->isExist = false;
        }
    }

}
