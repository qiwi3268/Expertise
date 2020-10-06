<?php


namespace Lib\Singles;

use Lib\Exceptions\PrimitiveValidator as SelfEx;
use functions\Exceptions\Functions as FunctionsEx;
use ReflectionMethod;
use ReflectionFunction;
use jsonException;
use BadMethodCallException;
use BadFunctionCallException;


/**
 * Предназначен для валидации (получения) примитивов
 *
 */
class PrimitiveValidator
{

    /**
     * Предназначен для получения массива, полученного декодированием входной json-строки, массив которого является
     * индексным массивом (без вложенных массивов) с только числовыми значениями
     *
     * @param string $json входной json
     * @param bool $checkSame требуется ли проверка на наличие одинаковых значений
     * @return array декодированный массив из json-строки
     * @throws SelfEx
     */
    public function getValidatedArrayFromNumericalJson(string $json, bool $checkSame): array
    {
        try {

            $array = json_decode($json, false, 2, JSON_THROW_ON_ERROR);
        } catch (jsonException $e) {

            $msg = "jsonException message: '{$e->getMessage()}', code: '{$e->getCode()}'";
            throw new SelfEx($msg, 1);
        }

        if (!is_array($array)) {
            throw new SelfEx("Декодированная json-строка: '{$json}' не является массивом", 2);
        }
        // Проверка массива на нечисловые значения
        foreach ($array as &$element) {

            if (($int = filter_var($element, FILTER_VALIDATE_INT)) === false) {
                throw new SelfEx("В массиве, полученном из json-строки, присутствует нечисловой элемент: '{$element}'", 3);
            }
            $element = $int;
        }
        unset($element);

        // Проверка массива на одинаковые значения
        if ($checkSame) {

            foreach (array_count_values($array) as $element => $count) {

                if ($count > 1) throw new SelfEx("В массиве, полученном из json-строки, элемент: '{$element}' найден: '{$count}' раз(а)", 4);
            }
        }
        return $array;
    }


    /**
     * Предназначен для получения ассоциативного массива, полученного декодированием входной json-строки
     *
     * @param string $json входной json
     * @param int $depth глубина рекурсии
     * @return array декодированный массив из json-строки
     * @throws SelfEx
     */
    public function getAssocArrayFromJson(string $json, int $depth = 512): array
    {
        try {

            return json_decode($json, true, $depth, JSON_THROW_ON_ERROR);
        } catch (jsonException $e) {

            $msg = "jsonException message: '{$e->getMessage()}', code: '{$e->getCode()}'";
            throw new SelfEx($msg, 1);
        }
    }


    /**
     * Предназначен для валидации строковой даты формата "дд.мм.гггг"
     *
     * @param string $fullDate дата формата "дд.мм.гггг"
     * @throws SelfEx
     */
    public function validateStringDate(string $fullDate): void
    {
        // начало текста
        // 1 группа:
        //    любая цифра 2 раза
        // точка
        // 2 группа:
        //    любая цифра 2 раза
        // точка
        // 3 группа:
        //    любая цифра 4 раза
        // конец текста
        $pattern = "/\A(\d{2})\.(\d{2})\.(\d{4})\z/";
        try {
            list(1 => $date, 2 => $month, 3 => $year) = getHandlePregMatch($pattern, $fullDate, false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Строковая дата: '{$fullDate}' является некорректной", 5);
        }

        if (!checkdate($month, $date, $year)) throw new SelfEx("Дата: '{$fullDate}' не существует по григорианскому календарю", 6);
    }


    /**
     * Предназначен для валидации ИНН
     *
     * @param string $INN ИНН (12 цифр для физ.лиц и 10 цифр для юр.лиц)
     * @throws SelfEx
     */
    public function validateINN(string $INN): void
    {
        // начало текста
        // любая цифра 10 раз
        // конец текста
        // ИЛИ
        // начало текста
        // любая цифра 12 раз
        // конец текста
        $pattern = "/\A\d{10}\z|\A\d{12}\z/";
        try {
            getHandlePregMatch($pattern, $INN, false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Введенный ИНН: '{$INN}' является некорректным", 7);
        }
    }


    /**
     * Предназначен для валидации КПП
     *
     * @param string $KPP КПП (9 цифр)
     * @throws SelfEx
     */
    public function validateKPP(string $KPP): void
    {
        // начало текста
        // любая цифра 9 раз
        // конец текста
        $pattern = "/\A\d{9}\z/";
        try {
            getHandlePregMatch($pattern, $KPP, false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Введенный КПП: '{$KPP}' является некорректным", 8);
        }
    }


    /**
     * Предназначен для валидации ОГРН
     *
     * @param string $OGRN ОГРН (13 цифр)
     * @throws SelfEx
     */
    public function validateOGRN(string $OGRN): void
    {
        // начало текста
        // любая цифра 13 раз
        // конец текста
        $pattern = "/\A\d{13}\z/";
        try {
            getHandlePregMatch($pattern, $OGRN, false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Введенный ОГРН: '{$OGRN}' является некорректным", 9);
        }
    }


    /**
     * Предназначен для валидации email
     *
     * @param string $email email
     * @throws SelfEx
     */
    public function validateEmail(string $email): void
    {
        if ((filter_var($email, FILTER_VALIDATE_EMAIL)) === false) {
            throw new SelfEx("Введенный email: '{$email}' является некорректным", 10);
        }
    }


    /**
     * Предназначен для валидации процента
     *
     * @param string $percent процент [0:100]
     * @throws SelfEx
     */
    public function validatePercent(string $percent): void
    {
        $options = [
            'options' => [
                'min_range' => 0,
                'max_range' => 100
            ]
        ];
        if ((filter_var($percent, FILTER_VALIDATE_INT, $options)) === false) {
            throw new SelfEx("Введенный процент: '{$percent}' является некорректным", 11);
        }
    }


    /**
     * Предназначен для валидации целочисленного значения
     *
     * @param string $int предполагаемое целочисленное значение
     * @throws SelfEx
     */
    public function validateInt(string $int): void
    {
        if (filter_var($int, FILTER_VALIDATE_INT) === false) {
            throw new SelfEx("Введеное значение: '{$int}' не является целочисленным", 12);
        }
    }


    /**
     * Предназначен для проверки обязательных элементов в ассоциативном массиве
     *
     * Метод проверяет их существование и проверяет принятыми callback'ами
     *
     * @param array $array проверяемый массив
     * @param array $settings <i>ключ</i> - элемент (ключ из array), который обязательно должен присутствовать в массиве<br>
     * <i>значение</i> - массив с callback'ами для проверки<br>
     * <b>callback</b> <i>строка</i> - функция для проверки<br>
     * или<br>
     * <b>callback</b> <i>массив</i> - [0 => экземпляр объекта или имя класса, 1 => имя метода, 2 => ..., 3 => ..., 4 => ...]<br>
     * Происходит вызов метода класса, в который <b>первым аргументом передается значение проверяемого массива</b>,
     * а остальные параметры - все, которые следуют за именем метода, т.е. 2, 3, 4 и т.д. элементы массива
     * @throws SelfEx
     */
    function validateAssociativeArray(array $array, array $settings): void
    {
        foreach ($settings as $key => $callbacks) {

            if (!array_key_exists($key, $array)) {
                throw new SelfEx("Во входном массиве отсутствует обязательное поле: '{$key}'", 13);
            }

            $result = false;

            // Хотя бы один callback должен вернуть true
            foreach ($callbacks as $callback) {

                // Проверка на существование принятого callback'а
                $is_array = is_array($callback);

                if ($is_array) {

                    if (!method_exists($callback[0], $callback[1])) throw new BadMethodCallException("Переданный метод: '{$callback[1]}' не существует'");

                    // Первый параметр - значение проверяемого массива
                    $params = [$array[$key]];
                    // Остальные параметры - все, что после названия класса и метода
                    for ($l = 2; $l < count($callback); $l++) {
                        $params[] = $callback[$l];
                    }

                    $res = call_user_func_array([$callback[0], $callback[1]], $params);

                } else {

                    if (!function_exists($callback)) throw new BadFunctionCallException("Переданная функция: '{$callback}' не существует");

                    $res = call_user_func($callback, $array[$key]);
                }

                // Положительным результатом проверки является:
                // true
                // null, т.к. многие методы не возвращают bool, а выбрасывают исключения
                // self для построения дальнейшей цепочки вызовов
                if (
                    $res === true
                    || is_null($res)
                    || ($is_array && is_object($res) && $res instanceof $callback[0])
                ) {
                    $result = true;
                    break 1;
                }
            }

            if (!$result) {
                throw new SelfEx("Значение входного массива по ключу: '{$key}' не прошло проверку", 14);
            }
        }
    }


    /**
     * Предназначен для проверки строгово равенства проверяемого значения value на один из необходимых параметров inclusions
     *
     * @param mixed $value проверяемое значение
     * @param mixed ...$inclusions <i>перечисление</i> необходимых параметров
     * @throws SelfEx
     */
    public function validateSomeInclusions($value, ...$inclusions): void
    {
        if (!in_array($value, $inclusions, true)) {

            // Формирование сообщения об ошибке
            $value .= ' (' . gettype($value) . ')';

            foreach ($inclusions as $l_key => $l_value) $inclusions[$l_key] .= ' (' . gettype($l_value) . ')';
            $msg = implode(' или ', $inclusions);

            throw new SelfEx("Значение: '{$value}' не подходит ни под одно из перечисленных: '{$msg}'", 15);
        }
    }


    /**
     * Предназначен для пророверки типа возвращаемого значения методом / функцией
     *
     * @param $function array|string  <i>array:</i> 0 => имя / экземпляр класса, 1 => название метода<br>
     * или<br>
     * <i>string:</i> название функции
     * @param string|null $type <i>string:</i> требуемый тип, формата 'string' / '?int' ..<br>
     * <i>null:</i> в методе / функции должно отсутствовать объявление типа возвращаемого значения
     * @throws SelfEx
     * @throws \ReflectionException
     */
    public function validateReturnType($function, ?string $type): void
    {
        if (is_array($function)) {
            $reflectionMethod = new ReflectionMethod($function[0], $function[1]);
            $reflectionType = $reflectionMethod->getReturnType();
            $debug = "{$reflectionMethod->getDeclaringClass()->getName()}::{$function[1]}";
        } else {
            $reflectionFunction = new ReflectionFunction($function);
            $reflectionType = $reflectionFunction->getReturnType();
            $debug = $function;
        }

        if (is_null($reflectionType)) {

            if (is_null($type)) return;

            throw new SelfEx("В требуемом методе / функции: '{$debug}' не объявлен тип возвращаемого значения", 16);
        }

        $typeName = $reflectionType->getName();

        if($reflectionType->allowsNull()) {
            $typeName = "?{$typeName}";
        }

        // Имеется тип возвращаемого значения, в то время как его не должно быть
        if (is_null($type)) {
            throw new SelfEx("Требуемый метод / функция: '{$debug}' имеет тип возвращаемого значения: '{$typeName}', когда его не должно быть", 17);
        }

        if ($typeName != $type) {
            throw new SelfEx("Требуемый метод / функция: '{$debug}' имеет тип возвращаемого значения: '{$typeName}' не равный требуемому: '{$type}'", 18);
        }
    }


    /**
     * Предназначен для проверки существования класса
     *
     * @param string $className название класса
     * @throws SelfEx
     */
    public function validateClassExist(string $className): void
    {
        if (!class_exists($className)) {
            throw new SelfEx("Класс: '{$className}' не существует", 19);
        }
    }


    /**
     * Предназначен для проверки существования метода класса
     *
     * @param string $className название класса
     * @param string $methodName название метода
     * @throws SelfEx
     */
    public function validateMethodExist(string $className, string $methodName): void
    {
        if (!method_exists($className, $methodName)) {
            throw new SelfEx("Метод: '{$className}::{$methodName}' не существует", 20);
        }
    }
}
