<?php


namespace Lib\CSP;

use Lib\Exceptions\CSPMessageParser as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use functions\Exceptions\Functions as FunctionsEx;
use Tables\people_name;


/**
 * Предназначен для разбора вывода исполняемой команды по валидации подписи
 *
 */
class MessageParser
{

    /**
     * Код, соответствующий успешному выполнению команды
     *
     */
    public const OK_ERROR_CODE = '0x00000000';

    /**
     * Хэш-массив популярных имен из БД
     *
     */
    private array $hashNames;


    /**
     * Конструктор класса
     *
     * @param bool $needNames флаг необходимости инициализировать массив имен.
     * Не нужен, если класс используется не для получения ФИО
     * @throws DataBaseEx
     */
    public function __construct(bool $needNames)
    {
        if ($needNames) {
            $names = people_name::getAllSimple();
            // Перевод выборки в формат хэш-массива
            foreach ($names as $name) $this->hashNames[$name] = true;
        }
    }


    /**
     * Предназначен для получения сообщения без технической его части:
     *
     * - CryptCP 4.0 (c) "Crypto-Pro", 2002-2020.
     * - CryptCP 5.0 (c) "Crypto-Pro", 2002-2019.
     * - Command prompt Utility for File signature and encryption.
     * - Folder '/var/www...'
     * - Signature verifying...
     * - ../../../../CSPbuild/CSP/samples/CPCrypt/DSign.cpp
     *
     * @param string $message вывод исполняемой команды по валидации подписи
     * @return array массив частей сообщения без технической части, разбитый по символам-переносам строк
     * @throws FunctionsEx
     */
    public function getMessagePartsWithoutTechnicalPart(string $message): array
    {
        $result = [];

        $parts = explode(PHP_EOL, $message);

        $this->checkOneLineParts($parts, "/.+Signature verifying\.\.\..*(\[ErrorCode:\s*.+])/iu", 'Signature verifying...', 'ErrorCode:');
        $this->checkOneLineParts($parts, "/.+Signature verifying\.\.\..*(Error:\s*.+)/iu", 'Signature verifying...', 'Error:');

        foreach ($parts as $part) {

            if (
                !icontainsAny(
                    $part,
                    'CryptCP 4.0 (c) "Crypto-Pro", 2002-2020.',
                    'CryptCP 5.0 (c) "Crypto-Pro", 2002-2019.',
                    'Command prompt Utility for file signature and encryption.',
                    'Folder',
                    'Signature verifying...',
                    'CSPbuild'
                )
                && $part !== ''
            ) {
                $result[] = trim($part); // Удаляем пробельные символы вначале и вконце строки
            }
        }
        return $result;
    }


    /**
     * Предназначен для проверки на то, не оказались ли нужные части в одной строке
     *
     * Возможны ситуации, когда из-за отсутствия прогресс-бара проверки подписи некоторые части сообщения
     * окажутся в одной строке, т.к. символ переноса строк принадлежит прогресс-бару.
     *
     * Например, Signature verifying и ErrorCode или Signature verifying и Error
     *
     * @param array $parts <i>ссылка</i> на массив частей сообщения
     * @param string $pattern шаблон для поиска нужно части, которая находится в 1 группе
     * @param string ...$needles <i>перечисление</i> необходимых вхождений
     * @throws FunctionsEx
     */
    private function checkOneLineParts(array &$parts, string $pattern, string ...$needles): void
    {
        foreach ($parts as $part) {

            if (call_user_func_array('icontainsAll', [$part, ...$needles])) {

                $parts[] = getHandlePregMatch($pattern, $part, false)[1];
                return;
            }
        }
    }


    /**
     * Предназначен для получения ФИО из строки вида - 'Signer: ...'
     *
     * @param string $signer строка с подписантом
     * @return string ФИО подписанта
     * @throws SelfEx
     * @throws FunctionsEx
     */
    public function getFIO(string $signer): string
    {

        // запятая ноль и более раз                 | если ФИО начинает строку
        // пробельный символ ноль и более раз       | если ФИО начинает строку или просто нет пробела после запятой
        // любой символ кириллицы один и более раз  | Фамилия
        // запятая
        // пробельный символ ноль и более раз       | просто нет пробела после запятой
        // любой символ кириллицы один и более раз  | Имя
        // пробельный символ
        // любой символ кириллицы один и более раз  | Отчество
        // запятая ноль и более раз                 | если ФИО завершает строку
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/,*\s*[а-яё]+,\s*[а-яё]+\s[а-яё]+,*/iu';

        $matches = getHandlePregMatch($pattern, $signer, true)[0]; // Массив полных вхождений шаблона

        $count = 0;             // Количество найденных ФИО
        $debug = [];            // Массив с фамилиями, именами и отчествами для вывода exception'а

        // Получаем слова
        // любой символ кириллицы один и более раз
        // - регистронезависимые
        // - использование кодировки utf-8
        $fio_pattern = '/[а-яё]+/iu';

        foreach ($matches as $match) {

            // Заменяем все ё на е, т.к. в БД хранятся только е
            $match = str_replace('ё', 'е', $match);

            $fio_matches = getHandlePregMatch($fio_pattern, $match, true)[0]; // Массив полных вхождений шаблона

            // Так как нет уверенности в том, что имя следует именно вторым, поэтому проверяем все слова
            foreach ($fio_matches as $part) {
                if (isset($this->hashNames[$part])) {
                    $result = implode(' ', $fio_matches);
                    $count++;
                    break;
                }
                $debug[] = $part;
            }
        }

        $debug = implode(', ', $debug);

        // В БД не нашлось подходящего имени
        if ($count === 0) throw new SelfEx("В БД не нашлось имени из ФИО: '{$debug}'", 1);

        // В одном Signer нашлось больше одного ФИО
        if ($count > 1) throw new SelfEx("В одном Signer: '{$signer}' нашлось больше одного ФИО", 2);

        return $result;
    }


    /**
     * Предназначен для получения данных о сертификате из строки вида - 'Signer: ...'
     *
     * @param string $signer строка с подписантом
     * @return string данные сертификата
     * @throws FunctionsEx
     */
    public function getCertificateInfo(string $signer): string
    {

        // Signer:
        // пробельный символ ноль и более раз
        // 1 группа:
        //    любой символ один и более раз
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/Signer:\s*(.+)/iu';
        return getHandlePregMatch($pattern, $signer, false)[1];
    }


    /**
     * Предназначен для получения кода ошибки из строки вида - '[ErrorCode: ... ]'
     *
     * @param string $message вывод исполняемой cryptcp команды
     * @return string код ошибки
     * @throws FunctionsEx
     */
    public function getErrorCode(string $message): string
    {

        // [ErrorCode:
        // пробельный символ ноль и более раз
        // 1 группа:
        //    любой символ один и более раз
        // ]
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/\[ErrorCode:\s*(.+)]/iu';
        return getHandlePregMatch($pattern, $message, false)[1];
    }
}
