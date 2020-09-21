<?php


namespace Classes\Application\Miscs\Validation;

use Lib\Exceptions\MiscValidator as MiscValidatorEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Classes\Exceptions\ApplicationFormMiscValidator as ApplicationFormMiscValidatorEx;
use LogicException;

use Lib\Miscs\Validation\Validator;
use Lib\Miscs\Validation\DependentMisc as MainDependentMisc;
use Classes\Application\DataToUpdate;


/**
 * Предназначен для валидации зависимых справочников формы анкеты документа <i>Заявление</i>
 *
 * <b>***</b> При валидации зависимого справочника предполагается, что главный справочник был провалидирован ранее
 */
class DependentMisc extends MainDependentMisc
{

    /**
     * Имя столбца справочника в БД
     *
     */
    private ?string $columnName;


    /**
     * Конструктор класса
     *
     * @param Validator $MainValidator объект валидации главного справочника
     * @param string $form_value
     * @param string $class
     * @param string|null $columnName <b>string</b> имя столбца справочнка в БД, если требуется добавление к массиву обновлений<br>
     * <b>null</b> добавление в массив обновлений не требудется, только валидация
     */
    public function __construct(Validator $MainValidator, string $form_value, string $class, ?string $columnName = null)
    {
        parent::__construct($MainValidator, $form_value, $class);

        $this->columnName = $columnName;
    }


    /**
     * Предназначен для комплексной проверки зависимого справочника
     *
     * @uses \Lib\Miscs\Validation\DependentMisc
     * @return $this
     * @throws MiscValidatorEx
     * @throws DataBaseEx
     * @throws ApplicationFormMiscValidatorEx
     */
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
                case 5 :
                    throw new ApplicationFormMiscValidatorEx($msg, 7);
                case 2 :
                case 3 :
                    throw new MiscValidatorEx($msg, $code);
            }
        }
        return $this;
    }


    /**
     * Предназначен для добавления значения справочника к массиву обновлений
     *
     * @throws LogicException
     */
    public function addToUpdate(): void
    {
        if (is_null($this->columnName)) {
            throw new LogicException("Попытка вызвать метод Classes\Application\Miscs\Validation::DependentMiscValidator::addToUpdate при неуказанном в конструкторе columnName. Название класса справочника: '{$this->class}'");
        }

        DataToUpdate::addInt($this->form_value, $this->columnName);
    }
}