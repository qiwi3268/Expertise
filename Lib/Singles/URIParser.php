<?php


namespace Lib\Singles;

use Lib\Exceptions\URIParser as SelfEx;
use functions\Exceptions\Functions as FunctionsEx;


/**
 * Предназначен для разбора URI с целью получения сведений о документе
 *
 */
class URIParser
{
    // начало строки
    // / ноль или один раз
    // home/expertise_cards/
    // 1 группа (тип документа):
    //    любой не пробельный символ один и более раз
    // /
    // 2 группа (режим просмотра):
    //    любой не пробельный символ один и более раз
    // конец строки
    public const EXPERTISE_CARD = '/^\/?home\/expertise_cards\/(\S+)\/(\S+)$/';

    // начало строки
    // / ноль или один раз
    // home/expertise_cards/
    // 1 группа (тип документа):
    //   любой не пробельный символ один и более раз
    // /actions/action_
    // любая цифра один и более раз
    // конец строки
    public const ACTION_PAGE = '/^\/?home\/expertise_cards\/(\S+)\/actions\/action_\d+$/';

    // начало строки
    // / ноль или один раз
    // 1 группа:
    //    home/expertise_cards/
    //    2 группа:
    //       любой не пробельный символ один и более раз
    //    /actions/action_
    //    любая цифра один и более раз
    // конец строки
    public const API_ACTION_EXECUTOR = '/^\/?(home\/expertise_cards\/(\S+)\/actions\/action_\d+)$/';


    /**
     * Предназначен для разбора URI карточки экспертизы
     *
     * @param string $URI
     * @return array ассоциативный массив формата:<br>
     * 'document_type' - тип документа<br>
     * 'document_id' - id документа<br>
     * 'view_mode' - режим просмотра<br>
     * @throws SelfEx
     */
    static public function parseExpertiseCard(string $URI): array
    {
        $result = self::getValidatedResult($URI);

        try {
            $pregResult = getHandlePregMatch(self::EXPERTISE_CARD, $result['path'], false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Произошла ошибка при разборе URI карточки экспертизы: '{$result['path']}'", 2001);
        }

        return [
            'document_type' => self::getValidatedDocumentType($pregResult[1]),
            'document_id'   => $result['params']['id_document'],
            'view_mode'     => $pregResult[2]
        ];
    }


    /**
     * Предназначен для разбора URI страницы действия
     *
     * @param string $URI
     * @return array ассоциативный массив формата:<br>
     * 'document_type' - тип документа<br>
     * 'document_id' - id документа<br>
     * @throws SelfEx
     */
    static public function parseActionPage(string $URI): array
    {
        $result = self::getValidatedResult($URI);

        try {
            $pregResult = getHandlePregMatch(self::ACTION_PAGE, $result['path'], false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Произошла ошибка при разборе URI страницы действия: '{$result['path']}'", 3001);
        }

        return [
            'document_type' => self::getValidatedDocumentType($pregResult[1]),
            'document_id'   => $result['params']['id_document']
        ];
    }


    /**
     * Предназначен для разбора URI страницы API выполнений действий
     *
     * @param string $URI
     * @return array ассоциативный массив формата:<br>
     * 'document_type' - тип документа<br>
     * 'document_id' - id документа<br>
     * 'page_name' - наименование страницы без первого '/' и GET-параметров
     * @throws SelfEx
     */
    static public function parseAPIActionExecutor(string $URI): array
    {
        $result = self::getValidatedResult($URI);

        try {
            $pregResult = getHandlePregMatch(self::API_ACTION_EXECUTOR, $result['path'], false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Произошла ошибка при разборе URI API выполнений действий: '{$result['path']}'", 4001);
        }

        return [
            'document_type' => self::getValidatedDocumentType($pregResult[2]),
            'document_id'   => $result['params']['id_document'],
            'page_name'     => $pregResult[1]
        ];
    }


    /**
     * Предназначен для разбора URI из открытой страницы любого типа
     *
     * Используется, когда заранее неизвестен тип URI
     *
     * @param string $URI
     * @return array ассоциативный массив формата:<br>
     * 'document_type' - тип документа<br>
     * 'document_id' - id документа<br>
     * @throws SelfEx
     */
    static public function parse(string $URI): array
    {
        $result = self::getValidatedResult($URI);
        $path = $result['path'];

        // i - индекс массива, по которому в шаблоне pattern находится тип документа

        if (containsAll($path, 'home/expertise_cards', 'actions/action_')) {

            $pattern = self::ACTION_PAGE;
            $i = 1;
        } elseif (contains($path, 'home/expertise_cards')) {

            $pattern = self::EXPERTISE_CARD;
            $i = 1;
        } elseif (contains($path, 'home/application/create')) {
            $documentType = DOCUMENT_TYPE['application'];
        } else {
            throw new SelfEx("Запрос: '{$path}' не определен", 5001);
        }

        if (isset($pattern, $i)) {
            try {
                $documentType = getHandlePregMatch($pattern, $path, false)[$i];
            } catch (FunctionsEx $e) {
                throw new SelfEx("Произошла ошибка при разборе URI страницы неизвестного типа: '{$path}'", 5002);
            }
        }

        return [
            'document_type' => self::getValidatedDocumentType($documentType),
            'document_id'   => $result['params']['id_document']
        ];
    }


    /**
     * Предназначен для получения пути запроса и массива GET-параметров
     *
     * @param string $URI
     * @return array ассоциативный массив формата:<br>
     * 'path' - путь запроса<br>
     * 'params' - массив GET-параметров, среди которых есть (int)'id_document'
     * @throws SelfEx
     */
    static private function getValidatedResult(string $URI): array
    {
        $parseUrl = parse_url($URI);

        if ($parseUrl === false) {
            throw new SelfEx("URI-адрес является некорректным", 1001);
        }

        if (!isset($parseUrl['path'])) {
            throw new SelfEx("В результатах разбора URI отсутствует элемент 'path'", 1002);
        }
        if (!isset($parseUrl['query'])) {
            throw new SelfEx("В результатах разбора URI отсутствует элемент 'query'", 1003);
        }

        parse_str($parseUrl['query'], $parseStr);

        if (!isset($parseStr['id_document'])) {
            throw new SelfEx("В результатах разбора URI отсутствует элемент 'id_document'", 1004);
        }

        if (!is_numeric($parseStr['id_document'])) {
            throw new SelfEx("Элемент 'id_document' не является числом или строкой, содержащей число", 1005);
        }

        $parseStr['id_document'] = (int)$parseStr['id_document'];

        return [
            'path'   => $parseUrl['path'],
            'params' => $parseStr
        ];
    }


    /**
     * Предназначен для получения проверенного типа документа
     *
     * @param string $documentType
     * @return string аналогичный входному параметру
     * @throws SelfEx
     */
    static private function getValidatedDocumentType(string $documentType): string
    {
        if (!isset(DOCUMENT_TYPE[$documentType])) {
            throw new SelfEx("Тип документа: '{$documentType}' не определен в константе DOCUMENT_TYPE", 6001);
        }
        return $documentType;
    }
}