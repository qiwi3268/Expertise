<?php


namespace Lib\CSP;

use Lib\Exceptions\CSPMessageParser as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Classes\Exceptions\PregMatch as PregMatchEx;
use Tables\people_name;


/**
 * Предназначен для парсинга вывода исполняемой команды по валидации подписи
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
     * @throws PregMatchEx
     */
    public function getMessagePartsWithoutTechnicalPart(string $message): array
    {
        $result = [];

        $parts = explode(PHP_EOL, $message);

        // Возможны ситуации, когда из-за отсутствия прогресс-бара проверки подписи Signature verifying и ErrorCode
        // окажутся в одной строке, т.к. символ переноса строк принадлежит прогресс-бару. В таком случае искусственно
        // добавляем блок ErrorCode к parts
        $tmp = array_filter($parts, fn($part) => icontainsAll($part, 'Signature verifying...', 'ErrorCode:'));
        if (!empty($tmp)) {

            $tmp = array_shift($tmp);

            // любой символ один и более раз
            // Signature verifying...
            // любой символ ноль и более раз
            // 1 группа:
            //    [ErrorCode:
            //    пробельный символ ноль и более раз
            //    любой символ один и более раз
            //    ]
            // - регистронезависимые
            // - использование кодировки utf-8
            $pattern = "/.+Signature verifying\.\.\..*(\[ErrorCode:\s*.+])/iu";
            $parts[] = getHandlePregMatch($pattern, $tmp, false)[1];
        }

        foreach ($parts as $part) {

            if (
                !icontainsAll($part, 'CryptCP 4.0 (c) "Crypto-Pro", 2002-2020.') &&
                !icontainsAll($part, 'CryptCP 5.0 (c) "Crypto-Pro", 2002-2019.') &&
                !icontainsAll($part, 'Command prompt Utility for file signature and encryption.') &&
                !icontainsAll($part, 'Folder') &&
                !icontainsAll($part, 'Signature verifying...') &&
                !icontainsAll($part, 'CSPbuild') &&
                $part !== ''
            ) {

                $result[] = trim($part); // Удаляем пробельные символы вначале и вконце строки
            }
        }
        return $result;
    }


    /**
     * Предназначен для получения ФИО из строки вида - 'Signer: ...'
     *
     * @param string $Signer строка с подписантом
     * @return string ФИО подписанта
     * @throws SelfEx
     * @throws PregMatchEx
     */
    public function getFIO(string $Signer): string
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

        $matches = getHandlePregMatch($pattern, $Signer, true)[0]; // Массив полных вхождений шаблона

        $count = 0;             // Количество найденных ФИО
        $FIOs = [];             // Массив с фамилиями, именами и отчествами для вывода exception'а

        // Получаем слова
        // любой символ кириллицы один и более раз
        // - регистронезависимые
        // - использование кодировки utf-8
        $fio_pattern = '/[а-яё]+/iu';

        foreach ($matches as $match) {

            // Заменяем все ё на е, т.е. в БД хранятся только е
            $match = str_replace('ё', 'е', $match);

            $fio_matches = getHandlePregMatch($fio_pattern, $match, true)[0]; // Массив полных вхождений шаблона

            // Так как нет уверенности в том, что имя следует именно вторым, поэтому проверяем все слова
            foreach ($fio_matches as $part) {
                if (isset($this->hashNames[$part])) {
                    $result = implode(' ', $fio_matches);
                    $count++;
                    break;
                }
                $FIOs[] = $part;
            }
        }

        $FIOs = implode(', ', $FIOs);

        // В БД не нашлось подходящего имени
        if ($count === 0) throw new SelfEx("В БД не нашлось имени из ФИО: '{$FIOs}'", 1);

        // В одном Signer нашлось больше одного ФИО
        if ($count > 1) throw new SelfEx("В одном Signer: '{$Signer}' нашлось больше одного ФИО: '{$FIOs}'", 2);

        return $result;
    }


    /**
     * Предназначен для получения данных о сертификате из строки вида - 'Signer: ...'
     *
     * @param string $Signer строка с подписантом
     * @return string данные сертификата
     * @throws PregMatchEx
     */
    public function getCertificateInfo(string $Signer): string
    {

        // Signer:
        // пробельный символ ноль и более раз
        // 1 группа:
        //    любой символ один и более раз
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = '/Signer:\s*(.+)/iu';
        return getHandlePregMatch($pattern, $Signer, false)[1]; // Возвращаем результат первой группы
    }


    /**
     * Предназначен для получения кода ошибки из строки вида - '[ErrorCode: ... ]'
     *
     * @param string $message вывод исполняемой cryptcp команды
     * @return string код ошибки
     * @throws PregMatchEx
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
        return getHandlePregMatch($pattern, $message, false)[1]; // Возвращаем результат первой группы
    }
}
