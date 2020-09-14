<?php


namespace Lib\Miscs\Validation;

use Lib\Exceptions\MiscValidator as SelfEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Singles\PrimitiveValidator;


/**
 * Абстрактный класс, предназначенный для валидации справочников
 *
 *  Предоставляет потомкам ({@see \Lib\Miscs\Validation\SingleMisc} и {@see \Lib\Miscs\Validation\DependentMisc})
 *  инкапсулированные свойства и интерфейс (методы) для валидации
 *
 */
abstract class Validator
{

    /**
     * Значение из формы
     *
     */
    protected string $form_value;

    /**
     * Название класса справочника
     *
     */
    protected string $class;

    /**
     * Полученное методом {@see Validator::getIntValue()} int'овое значение справочника
     *
     */
    protected ?int $int_value = null;

    /**
     * Флаг наличия проверенных введенных данных справочника
     *
     */
    protected ?bool $isExist = null;


    /**
     * Предназначен для получения проверенного значения справочника из формы
     *
     * @param string $form_value значение из формы
     * @return int преобразованное к int'у значение справочника
     * @throws SelfEx
     */
    protected function getValidatedInt(string $form_value): int
    {
        $PrimitiveValidator =  new PrimitiveValidator();
        try {
            $PrimitiveValidator->validateInt($form_value);
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Передано некорректное значение справочника: '{$form_value}'", 1);
        }
        return $form_value;
    }


    /**
     * Предназначен для проверки существования указанного класса и реализацию нужного интерфейса
     *
     * @param string $class название класса справочника
     * @param string $interface требуемый для реализации интерфейс
     * @throws SelfEx
     */
    protected function checkClass(string $class, string $interface): void
    {
        if (!class_exists($class)) {
            throw new SelfEx("Класс справочника: '{$class}' не существует", 2);
        }

        $interfaces = class_implements($class);

        if (!$interfaces || !in_array($interface, $interfaces, true)) {
            throw new SelfEx("Класс: '{$class}' не реализует интерфейс: '{$interface}'", 3);
        }
    }


    /**
     * Предназначен для получения существования запрашиваемого класса
     *
     * @param string $class название класса справочника
     * @param string $method метод для проверки существования класса
     * @param array $params параметры запроса
     * @throws SelfEx
     */
    protected function checkMiscExist(string $class, string $method, array $params): void
    {
        // Произошла ошибка при вызове функции или метод вернул отрицательный результат
        if (!call_user_func_array([$class, $method], $params)) {
            $params = implode(', ', $params);
            throw new SelfEx("Запрашиваемое значение справочника с параметрами запроса: '{$params}' не существует в таблице класса: '{$class}'", 4);
        }
    }


    /**
     * Предназначен для получения флага наличия проверенных введенных данных справочника
     *
     * @return bool флаг наличия проверенных введенных данных справочника
     * @throws \LogicException
     */
    public function isExist(): bool
    {
        if (is_null($this->isExist)) {
            throw new \LogicException("Попытка вызвать метод Lib\Miscs\Validation\Validator::isExist при значении свойства isExist = null. Название класса справочника: '{$this->class}'");
        }
        return $this->isExist;
    }


    /**
     * Предназначен для получения int'ового значения справочника
     *
     * @return int преобразованное к int значение справочника
     * @throws \LogicException
     */
    public function getIntValue(): int
    {
        if (is_null($this->int_value)) {
            throw new \LogicException("Попытка вызвать метод Lib\Miscs\Validation\Validator::getIntValue при значении свойства int_value = null. Название класса справочника: '{$this->class}'");
        }
        return $this->int_value;
    }


    /**
     * Предназначен для комплексной проверки справочника
     *
     * <b>*</b> Возвращаемый тип не объявлен, чтобы дочерние классы при желании могли реализовать цепочки вызовов
     *
     * @return mixed
     */
    abstract public function validate();
}
