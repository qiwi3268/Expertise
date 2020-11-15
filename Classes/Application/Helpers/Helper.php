<?php


namespace Classes\Application\Helpers;

use functions\Exceptions\Functions as FunctionsEx;


/**
 *  Предназначен для вспомогательной работы с типом документа <i>Заявление</i>
 *
 */
class Helper
{

    /**
     * Предназначен для получения числового имени заявления
     *
     * Дополняет внутренний счетчик ведущим нулем
     *
     * @param int $internalCounter внутренний счетчик заявления
     * @return string числовое имя
     */
    static public function getInternalAppNumName(int $internalCounter): string
    {

        $nowDate = date('Y-m');

        if ($internalCounter < 10) {
            $internalCounter = str_pad($internalCounter, 2, '0', STR_PAD_LEFT);
        }

        return "{$nowDate}-{$internalCounter}";
    }


    /**
     * Предназначен для разбора абсолютного пути в ФС серерва к файлу из директории заявления
     *
     * @param string $path абсолютный путь в ФС сервера к файлу
     * @return array ассоциативный массив с параметрами:<br>
     * 'application_id' <i>int</i> => id заявления<br>
     * 'file_name' <i>string</i> => имя файла в ФС
     *
     * @throws FunctionsEx
     */
    static public function parseApplicationFilePath(string $path): array
    {

        // Экранирование
        $applicationDir = preg_quote(APPLICATIONS_FILES, '/');

        // начало строки
        // директория файлов заявлений
        // слэш
        // 1 группа:
        //    любая цифра один и более раз
        // слэш
        // 2 группа:
        //   любой не пробельный символ один и более раз
        // конец строки
        $pattern = "/^{$applicationDir}\/(\d+)\/(\S+)$/";
        $matches = getHandlePregMatch($pattern, $path, false);

        return [
            'application_id' => (int)$matches[1],
            'file_name'      => $matches[2]
        ];
    }
}
