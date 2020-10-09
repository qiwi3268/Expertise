<?php


namespace Lib\Singles;

use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;
use BadMethodCallException;


/**
 * Обертка для класса {@see \Lib\Singles\PrimitiveValidator}
 *
 * Возвращает bool значение вместо выбрасывания исключения.
 * Оборачивать можно только те методы, которые возращают тип void
 */
class PrimitiveValidatorBoolWrapper
{

    private PrimitiveValidator $validator;


    /**
     * Констркутор класса
     *
     * @param PrimitiveValidator|null $validator экземпляр класса валидатора.<br>
     * Если <i>null</i>, то будет создан новый экземпляр валидатора
     */
    public function __construct(?PrimitiveValidator $validator = null)
    {
        if (is_null($validator)) {
            $validator = new PrimitiveValidator();
        }
        $this->validator = $validator;
	}


    /**
     * Предназначен для преобразования исключений в bool результат
     *
     * @param string $method название метода в классе {@see \Lib\Singles\PrimitiveValidator}
     * @param array $params массив параметров, которые будут переданы в оборачиваемый метод
     * @return bool <b>true</b> вызываемый метод не бросил исключение<br>
     * <b>false</b> в противном случае
     * @throws BadMethodCallException
     */
    public function __call(string $method, array $params): bool
    {
        try {

            $this->validator->validateReturnType([$this->validator, $method], 'void');
        } catch (ReflectionException $e) {

            throw new BadMethodCallException("Запрашиваемый метод: '{$method}' не существует в классе Lib\Singles\PrimitiveValidator");
        } catch (PrimitiveValidatorEx $e) {

            throw new BadMethodCallException("В запрашиваемом методе: '{$method}' должен быть объявлен возвращаемый тип void");
        }

        try {

            call_user_func_array([$this->validator, $method], $params);
            return true;
        } catch (PrimitiveValidatorEx $e) {

            return false;
        }
    }
}