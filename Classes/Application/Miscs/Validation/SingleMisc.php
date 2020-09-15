<?php


namespace Classes\Application\Miscs\Validation;

use Lib\Exceptions\MiscValidator as MiscValidatorEx;
use Lib\Miscs\Validation\SingleMisc as MainSingleMisc;
use Classes\Exceptions\ApplicationFormMiscValidator as ApplicationFormMiscValidatorEx;
use Classes\Application\DataToUpdate;


class SingleMisc extends MainSingleMisc
{
    private ?string $columnName; // Имя столбца справочника в БД

    public function __construct(string $form_value, string $class, ?string $columnName = null)
    {
        parent::__construct($form_value, $class);

        $this->columnName = $columnName;
    }


    public function validate(): self
    {
        try {

            parent::validate();
        } catch (MiscValidatorEx $e) {

            $msg = $e->getMessage();
            $code = $e->getCode();
            // Конвертируем значения MiscValidatorEx к значениям API_save_form result
            switch ($code) {
                case 1 :
                    throw new ApplicationFormMiscValidatorEx($msg, 4);
                case 4 :
                    throw new ApplicationFormMiscValidatorEx($msg, 5);
                case 2 :
                case 3 :
                    throw new MiscValidatorEx($msg, $code);
            }
        }
        return $this;
    }


    // Предназначен для добавления значения справочника к массиву обновлений
    //
    public function addToUpdate(): void
    {
        if (is_null($this->columnName)) {
            throw new \LogicException("Попытка вызвать метод Classes\Application\Miscs\Validation\SingleMiscValidator::addToUpdate при неуказанном в конструкторе columnName. Название класса справочника: '{$this->class}'");
        }

        DataToUpdate::addInt($this->form_value, $this->columnName);
    }
}