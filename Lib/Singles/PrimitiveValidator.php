<?php


namespace Lib\Singles;

use Lib\Exceptions\PrimitiveValidator as SelfEx;
use Classes\Exceptions\PregMatch as PregMatchEx;


// Предназначен для валидации (получения) примитивов
class PrimitiveValidator
{

    // Предназначен для получения массива, полученного декодированием входной json-строки, массив которого является
    // индексным массивом (без вложенных массивов) с только числовыми значениями
    // Принимает параметры-----------------------------------
    // json string : входной json
    // checkSame   : bool требуется ли проверка на наличие одинаковых значений
    // Возвращает параметры----------------------------------
    // array : декодированный массив из json-строки
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    //  1 - ошибка при декодировании json-строки
    //  2 - декодированная json-строка не является массивом
    //  3 - в массиве, полученном из json-строки, присутствует нечисловой элемент
    //  4 - в массиве, полученном из json-строки элемент найден более одного раза
    //
    public function getValidatedArrayFromNumericalJson(string $json, bool $checkSame): array
    {
        try {

            $array = json_decode($json, false, 2, JSON_THROW_ON_ERROR);
        } catch (\jsonException $e) {

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


    // Предназначен для получения ассоциативного массива, полученного декодированием входной json-строки
    // Принимает параметры-----------------------------------
    // json string : входной json
    // Возвращает параметры----------------------------------
    // array : декодированный массив из json-строки
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    //  1 - ошибка при декодировании json-строки
    //
    public function getAssocArrayFromJson(string $json): array
    {
        try {

            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\jsonException $e) {

            $msg = "jsonException message: '{$e->getMessage()}', code: '{$e->getCode()}'";
            throw new SelfEx($msg, 1);
        }
    }


    // Предназначен для валидации строковой даты формата "дд.мм.гггг"
    // Принимает параметры-----------------------------------
    // fullDate string : дата формата "дд.мм.гггг"
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    //  5 - строковая дата является некорректной
    //  6 - дата не существует по григорианскому календарю
    //
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
            list(1 => $date, 2 => $month, 3 => $year) = GetHandlePregMatch($pattern, $fullDate, false);
        } catch (PregMatchEx $e) {
            throw new SelfEx("Строковая дата: '{$fullDate}' является некорректной", 5);
        }

        if (!checkdate($month, $date, $year)) throw new SelfEx("Дата: '{$fullDate}' не существует по григорианскому календарю", 6);
    }


    // Предназначен для валидации ИНН
    // Принимает параметры-----------------------------------
    // INN string : ИНН (12 цифр для физ.лиц и 10 цифр для юр.лиц)
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    //  7 - введенный ИНН является некорректным
    //
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
            GetHandlePregMatch($pattern, $INN, false);
        } catch (PregMatchEx $e) {
            throw new SelfEx("Введенный ИНН: '{$INN}' является некорректным", 7);
        }
    }


    // Предназначен для валидации КПП
    // Принимает параметры-----------------------------------
    // KPP string : КПП (9 цифр)
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    //  8 - введенный КПП является некорректным
    public function validateKPP(string $KPP): void
    {
        // начало текста
        // любая цифра 9 раз
        // конец текста
        $pattern = "/\A\d{9}\z/";
        try {
            GetHandlePregMatch($pattern, $KPP, false);
        } catch (PregMatchEx $e) {
            throw new SelfEx("Введенный КПП: '{$KPP}' является некорректным", 8);
        }
    }


    // Предназначен для валидации ОГРН
    // Принимает параметры-----------------------------------
    // OGRN string : ОГРН (13 цифр)
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    //  9 - введенный ОГРН является некорректным
    public function validateOGRN(string $OGRN): void
    {
        // начало текста
        // любая цифра 13 раз
        // конец текста
        $pattern = "/\A\d{13}\z/";
        try {
            GetHandlePregMatch($pattern, $OGRN, false);
        } catch (PregMatchEx $e) {
            throw new SelfEx("Введенный ОГРН: '{$OGRN}' является некорректным", 9);
        }
    }


    // Предназначен для валидации email
    // Принимает параметры-----------------------------------
    // email string : email
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    // 10 - введенный email является некорректным
    //
    public function validateEmail(string $email): void
    {
        if ((filter_var($email, FILTER_VALIDATE_EMAIL)) === false) {
            throw new SelfEx("Введенный email: '{$email}' является некорректным", 10);
        }
    }


    // Предназначен для валидации процента
    // Принимает параметры-----------------------------------
    // percent string : процент [0:100]
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    // 11 - введенный процент является некорректным
    //
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


    // Предназначен для валидации целочисленного значения
    // Принимает параметры-----------------------------------
    // int string : предполагаемое целочисленное значение
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    // 12 - введеное значение не является целочисленным
    //
    public function validateInt(string $int): void
    {
        if (filter_var($int, FILTER_VALIDATE_INT) === false) {
            throw new SelfEx("Введеное значение: '{$int}' не является целочисленным", 12);
        }
    }


    // Предназначен для проверки обязательных элементов в ассоциативном массиве. Метод проверяет их существование и проверяет принятыми callback'ами
    // Принимает параметры-----------------------------------
    // array    array : проверяемый массив
    // settings array : ключ - элемент (ключ из array), который обязательно должен присутствовать в массиве. Значение - массив с callback'ами для проверки
    // Если callback строка - функция для проверки
    //               массив - [0 => экземпляр объекта или имя класса, 1 => имя метода, 2 => ..., 3 => ..., 4 => ...]
    //               Происходит вызов метода класса, в который первым аргументом передается значение проверяемого массива, а остальные параметры - все, которые
    //               следуют за именем метода, т.е. 2, 3, 4 и т.д. элементы массива
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    // 13 - во входном массиве отсутствует обязательное поле
    // 14 - значение входного массива по ключу не прошло проверку
    //
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

                    if (!method_exists($callback[0], $callback[1])) throw new \BadMethodCallException("Переданный метод: '{$callback[1]}' не существует'");

                    // Первый параметр - значение проверяемого массива
                    $params = [$array[$key]];
                    // Остальные параметры - все, что после названия класса и метода
                    for ($l = 2; $l < count($callback); $l++) {
                        $params[] = $callback[$l];
                    }

                    $res = call_user_func_array([$callback[0], $callback[1]], $params);

                } else {

                    if (!function_exists($callback)) throw new \BadFunctionCallException("Переданная функция: '{$callback}' не существует");

                    $res = call_user_func($callback, $array[$key]);
                }

                // Положительным результатом проверки является:
                // true
                // null, т.к. многие методы не возвращают bool, а выбрасывают исключения
                // self для построения дальнейшей цепочки вызовов
                if ($res === true || is_null($res) || ($is_array && is_object($res) && $res instanceof $callback[0])) {
                    $result = true;
                    break 1;
                }
            }

            if (!$result) {
                throw new SelfEx("Значение входного массива по ключу: '{$key}' не прошло проверку", 14);
            }
        }
    }


    // Предназначен для проверки строгово равенства проверяемого значения value на один из необходимых параметров inclusions
    // Принимает параметры-----------------------------------
    // value      : проверяемое значение
    // inclusions : перечисление необходимых параметров
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\PrimitiveValidator :
    // code:
    // 15 - значение не подходит ни под одно из перечисленных
    //
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
}
