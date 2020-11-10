<?php


namespace Lib\Singles;
use Exception as SelfEx;


class URIParser
{

    static public function parseExpertiseCard(string $URI): ?array
    {
        //uri без разницы есть первый / или нет
        //ну и фиг с ним, дальше на регулярке упадет или сделать что может есть а может нет
        $result = self::getValidatedResult($URI);

        $path = $result['path'];
        $id_document = $result['params']['id_document'];

        

        vd($result);
        return null;
    }

    static private function getValidatedResult(string $URI): array
    {
        $parseUrl = parse_url($URI);

        if ($parseUrl === false) {
            throw new SelfEx("URI-адрес является некорректным", 1);
        }

        if (!isset($parseUrl['path'])) {
            throw new SelfEx("В результатах разбора URI отсутствует элемент 'path'", 2);
        }
        if (!isset($parseUrl['query'])) {
            throw new SelfEx("В результатах разбора URI отсутствует элемент 'query'", 3);
        }

        parse_str($parseUrl['query'], $parseStr);

        if (!isset($parseStr['id_document'])) {
            throw new SelfEx("В результатах разбора URI отсутствует элемент 'id_document'", 4);
        }

        if (!is_numeric($parseStr['id_document'])) {
            throw new SelfEx("Элемент 'id_document' не является числом или строкой, содержащей число", 5);
        }

        $parseStr['id_document'] = (int)$parseStr['id_document'];

        return [
            'path'   => $parseUrl['path'],
            'params' => $parseStr
        ];
    }
}