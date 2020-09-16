<?php

use Classes\Exceptions\PregMatch as PregMatchEx;


/**
 * Предназначен для включения пользовательской автозагрузки
 *
 * @throws PregMatchEx
 */
function enableAutoloadRegister(): void
{

    spl_autoload_register(function (string $className) {

        $path = null;

        if (containsAll($className, '\\')) {

            $namespacePath = str_replace('\\', '/', $className);

            $pattern = "/\A(.+)\/(.+\z)/";

            list(1 => $tmp_path, 2 => $tmp_name) = getHandlePregMatch($pattern, $namespacePath, false);

            $path = ROOT . "/{$tmp_path}/{$tmp_name}.php";
        }

        if (!is_null($path) && file_exists($path)) require_once $path;
    });
}


/**
 * Предназначен для проверки наличия требуемых параметров в <b>POST</b> запросе
 *
 * @param string ...$params <i>перечисление</i> необходимых параметров
 * @return bool <b>true</b> все принятые параметры присутствуют в массиве POST (на первом уровне вложенности)<br/><b>false</b> в противном случае
 */
function checkParamsPOST(string ...$params): bool
{
    foreach ($params as $param) {
        if (!isset($_POST[$param])) {
            return false;
        }
    }
    return true;
}


/**
 * Предназначен для проверки наличия требуемых параметров в <b>GET</b> запросе
 *
 * @param string ...$params <i>перечисление</i> необходимых параметров
 * @return bool <b>true</b> все принятые параметры присутствуют в массиве GET (на первом уровне вложенности)<br/><b>false</b> в противном случае
 */
function checkParamsGET(string ...$params): bool
{
    foreach ($params as $param) {
        if (!isset($_GET[$param])) {
            return false;
        }
    }
    return true;
}


/**
 * Предназначен для очистки массива от html тегов (на первых двух уровнях вложенности)
 *
 * @param array $arr массив для очистки
 * @return array очищенный массив
 */
function clearHtmlArr(array $arr): array
{
    $clearArr = [];

    // key1 value1 - первый уровень вложенности
    foreach ($arr as $key1 => $value1) {

        if (is_array($value1)) {

            $tmpArr = [];
            // key2 value2 - второй уровень вложенности
            foreach ($value1 as $key2 => $value2) {

                if (!is_array($value2)) {
                    // ENT_NOQUOTES - оставляет без изменений одинарные и двойные кавычки
                    $tmpArr[$key2] = htmlspecialchars(strip_tags($value2), ENT_NOQUOTES);
                }
            }
            $clearArr[$key1] = $tmpArr;
        } else {

            $clearArr[$key1] = htmlspecialchars(strip_tags($value1), ENT_NOQUOTES);
        }
    }

    return $clearArr;
}

//todo среднее вынести в date helper
function getDdMmYyyyDate(int $timestamp): string
{
    return date('d.m.Y', $timestamp);
}


/**
 * Предназначен для перезаписи дат в ассоциативном массиве из timestamp в 'dd.mm.yyyy'
 *
 * @param array $assocArray <i>ссылка</i> на ассоциативный массив
 * @param string ...$datePropertyNames <i>перечисление</i> названий свойств, в которых находятся даты в формате timestamp
 */
function updateDatesTimestampToDdMmYyyy(array &$assocArray, string ...$datePropertyNames): void
{
    foreach ($datePropertyNames as $propertyName) {

        $timeStamp = $assocArray[$propertyName];

        if (is_numeric($timeStamp)) {
            $assocArray[$propertyName] = date('d.m.Y', $timeStamp);
        }
    }
}


/**
 * Предназначен для поиска вхождения подстроки в строку
 *
 * <b>Регистрозависимый поиск</b><br>
 * Если передано несколько подсрок needles, то <b>true</b> будет в случае вхождения всех подстрок
 *
 * @param string $haystack строка, в которой производится поиск
 * @param string ...$needles <i>перечисление</i> подстрок
 * @return bool <b>true</b> все подстроки присутствуют в строке<br/><b>false</b> в противном случае
 */
function containsAll(string $haystack, string ...$needles): bool
{
    foreach ($needles as $needle) {
        if (mb_strpos($haystack, $needle) === false) return false;
    }
    return true;
}


/**
 * Предназначен для поиска вхождения подстроки в строку
 *
 * <b>Регистронезависимый поиск</b><br>
 * Если передано несколько подсрок needles, то <b>true</b> будет в случае вхождения всех подстрок
 *
 * @param string $haystack строка, в которой производится поиск
 * @param string ...$needles <i>перечисление</i> подстрок
 * @return bool <b>true</b> все подстроки присутствуют в строке<br/><b>false</b> в противном случае
 */
function icontainsAll(string $haystack, string ...$needles): bool
{
    foreach ($needles as $needle) {
        if (mb_stripos($haystack, $needle) === false) return false;
    }
    return true;
}


/**
 * Предназначен для поиска вхождения подстроки в строку
 *
 * <b>Регистрозависимый поиск</b><br>
 * Если передано несколько подсрок needles, то <b>true</b> будет в случае хотя бы одной подстроки
 *
 * @param string $haystack строка, в которой производится поиск
 * @param string ...$needles <i>перечисление</i> подстрок
 * @return bool <b>true</b> все подстроки присутствуют в строке<br/><b>false</b> в противном случае
 */
function containsAny(string $haystack, string ...$needles): bool
{
    foreach ($needles as $needle) {
        if (mb_strpos($haystack, $needle) !== false) return true;
    }
    return false;
}


/**
 * Предназначен для получения массива совпавших значений с учетом обработки результата работы функции
 *
 * Результатом работы функции обязательно должно быть вхождение шаблона
 *
 * @param string $pattern искомый шаблон
 * @param string $subject входная строка
 * @param bool $is_preg_match_all в ходе работы метода будет выполняться функция:<br><b>true</b> preg_match_all<br/><b>false</b> preg_match
 * @return array массив совпавших значений
 * @throws PregMatchEx
 */
function getHandlePregMatch(string $pattern, string $subject, bool $is_preg_match_all): array
{
    $functionName = $is_preg_match_all ? 'preg_match_all' : 'preg_match';
    $matches = null;
    $result = $functionName($pattern, $subject, $matches);

    // Во время выполнения произошли ошибки или нет вхождений шаблона
    if ($result === false || $result === 0) {
        throw new PregMatchEx("Во время выполнения функции: '{$functionName}' произошла ошибка или нет вхождений шаблона: '{$pattern}' в строку: '{$subject}'", 1);
    }

    return $matches;
}


/**
 * Предназначен для подсчета количества элементов в ассоцивтивном массиве
 *
 * @param array $array индексный массив с ассоциативными массивами внутри
 * @param string $key
 * @param mixed $value
 * @return array
 */
function arrayEntry(array $array, string $key, $value): array
{
    $count = 0;
    $indexes = null;

    foreach ($array as $k => $v) {

        if ($v[$key] === $value) {
            $count++;
            $indexes[$count] = $k;
        }
    }
    return [
        'count'   => $count,
        'indexes' => $indexes
    ];
}



/**
 * Предназначен для перевода байт в человекопонятный формат
 *
 * @param int $bytes размер файла в байтах
 * @return string строка формата: <i>20,65 Мб</i>
 */
function getHumanFileSize(int $bytes): string
{
    if ($bytes == 0) return '0 Б';

    foreach (array_reverse(['Б', 'Кб', 'Мб', 'Гб', 'Тб'], true) as $exp => $label) {

        if ($bytes >= ($value = pow(1024, $exp))) {
            return str_replace('.', ',', round(($bytes / $value), 2) . " {$label}");
        }
    }
}


/**
 * Предназначен для получения ФИО из ассоциативного массива пользователя
 *
 * @param array $userInfo ассоциативный массив, в котором есть ключи: last_name, first_name, middle_name
 * @param bool $needShort <b>true</b> возвращать короткую версию ФИО<br/><b>false</b> возвращать полную версию ФИО
 * @return string ФИО в нужном формате
 * @throws PregMatchEx
 */
function getFIO(array $userInfo, bool $needShort = true): string
{
    list('last_name' => $F, 'first_name' => $I, 'middle_name' => $O) = $userInfo;

    $full = is_null($O) ? "{$F} {$I}" : "{$F} {$I} {$O}";

    if ($needShort) {

        // начало текста
        // 1 группа:
        //    любой символ кириллицы один и более раз
        // пробельный символ
        // 2 группа:
        //    любой символ кириллицы один раз
        // любой символ кириллицы один и более раз
        // 3 группа: (от 0 до 1 раз)
        //    пробельный символ
        //    4 группа:
        //       любой символ кириллицы один раз
        //    любой символ кириллицы один и более раз
        // конец текста
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/\A([а-яё]+)\s([а-яё]{1})[а-яё]+(\s([а-яё]{1})[а-яё]+)?\z/iu';

        $m = getHandlePregMatch($pattern, $full, false);

        return isset($m[4]) ? "{$m[1]} {$m[2]}.{$m[4]}." : "{$m[1]} {$m[2]}.";

    } else {
        return $full;
    }
}


/**
 * Возвращает хэш-массив на основе входного индексного массива
 *
 * [0 => 'a', 1 => 'b', 2 => 'c'] трансформирует в ['a' => true, 'b' => true, 'c' => true]
 *
 * @param array $array индексный массив
 * @return array хэш-массив
 */
function getHashArray(array $array): array
{
    $result = [];
    foreach ($array as $elem) {
        $result[$elem] = true;
    }
    return $result;
}


/**
 * Предназначен для форматированного вывода var_dump
 *
 * Вывод виден только тем пользователем, у которых в <b>get</b>-параметре присутствует <i>debug=1</i>
 * @param $arg
 */
function p($arg): void
{
    if (isset($_GET['debug']) && $_GET['debug'] == 1) {
        var_dump($arg);
    }
}










