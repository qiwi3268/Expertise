<?php


namespace Lib\Singles;

use Lib\Exceptions\XMLValidator as SelfEx;
use SimpleXMLElement;


class XMLValidator
{

    // Предназначен для валидации аттрибутов проверяемого XML-узла
    // Проверяет, что у узла есть все из перечисленных обязательных аттрибутов и при этом нет лишних
    // Опциональные аттрибутов могут присутствовать или отсутствовать
    // Принимает параметры-----------------------------------
    // node    SimpleXMLElement : проверяемый XML-узел
    // debugName         string : имя узла для его вывода в дамп ошибки
    // requiredAttributes array : индексный массив с перечислением названий обязательных аттрибутов
    // optionalAttributes array : индексный массив с перечислением названий опциональных аттрибутов
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\XMLValidator :
    // code:
    //  1 - в узле не найден обязательный аттрибут
    //  2 - в узле имеются аттрибуты, в то время как их не должно быть
    //
    public function validateAttributes(SimpleXMLElement $node, string $debugName, array $requiredAttributes, array $optionalAttributes = []): void
    {
        // Получение массива аттрибутов узла из XML-объекта
        $arr_attributes = (array)$node->attributes() ?? [];
        $attributes = isset($arr_attributes['@attributes']) ? array_keys($arr_attributes['@attributes']) : [];

        // Строка для дампа ошибок
        $dump_1 = implode(', ', $attributes);
        $dump_2 = implode(', ', [...$requiredAttributes, ...$optionalAttributes]);

        $attributes = getHashArray($attributes);

        foreach ($requiredAttributes as $requiredAttribute) {

            if (!isset($attributes[$requiredAttribute])) {

                if (empty($dump_1)) {
                    $message = "В узле: '{$debugName}' не найден обязательный аттрибут '{$requiredAttribute}'";
                } else {
                    $message = "В узле: '{$debugName}' среди аттрибутов: '{$dump_1}' не найден обязательный аттрибут '{$requiredAttribute}'";
                }

                throw new SelfEx($message, 1);
            }
        }

        $entryCount = count($requiredAttributes);

        foreach ($optionalAttributes as $optionalAttribute) {
            if (isset($attributes[$optionalAttribute])) {
                $entryCount++;
            }
        }

        if (count($attributes) != $entryCount) {

            if (empty($dump_2)) {
                $message = "В узле: '{$debugName}' имеются аттрибуты, в то время как их не должно быть";
            } else {
                $message = "В узле: '{$debugName}' имеются аттрибуты помимо: '{$dump_2}'";
            }

            throw new SelfEx($message, 2);
        }
    }


    // Предназначен для валидации дочерних узлов проверяемого XML-узла
    // Проверяет, что у узла есть все из перечисленных обязательных дочерних узлов и при этом нет лишних
    // Опциональные дочерние узлы могут присутствовать или отсутствовать
    // Принимает параметры-----------------------------------
    // node  SimpleXMLElement : проверяемый XML-узел
    // debugName       string : имя узла для его вывода в дамп ошибки
    // requiredChildren array : индексный массив с перечислением названий обязательных дочерних узлов
    // optionalChildren array : индексный массив с перечислением названий опциональных дочерних узлов
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\XMLValidator :
    // code:
    //  3 - в узле не найден обязательный дочерний узел узел
    //  4 - в узле имеются дочерние узлы, в то время как их не должно быть
    //
    public function validateChildren(SimpleXMLElement $node, string $debugName, array $requiredChildren, array $optionalChildren = [])
    {
        // Берем только названия дочерних узлов
        $childrenName = array_filter(array_keys((array)$node), fn($el) => ($el != '@attributes'));

        // Строка для дампа ошибок
        $dump_1 = implode(', ', $childrenName);
        $dump_2 = implode(', ', [...$requiredChildren, ...$optionalChildren]);

        $childrenName = getHashArray($childrenName);

        foreach ($requiredChildren as $requiredName) {

            if (!isset($childrenName[$requiredName])) {

                if (empty($dump_1)) {
                    $message = "В узле: '{$debugName}' не найден обязательный дочерний узел узел '{$requiredName}'";
                } else {
                    $message = "В узле: '{$debugName}' среди дочерних узлов: '{$dump_1}' не найден обязательный узел '{$requiredName}'";
                }

                throw new SelfEx($message, 3);
            }
        }

        $entryCount = count($requiredChildren);

        foreach ($optionalChildren as $optionalName) {
            if (isset($childrenName[$optionalName])) {
                $entryCount++;
            }
        }

        if (count($childrenName) != $entryCount) {

            if (empty($dump_2)) {
                $message = "В узле: '{$debugName}' имеются дочерние узлы, в то время как их не должно быть";
            } else {
                $message = "В узле: '{$debugName}' имеются дочерние узлы помимо: '{$dump_2}'";
            }

            throw new SelfEx($message, 4);
        }
    }


    // Предназначен для получения уникального узла XPath пути
    // Принимает параметры-----------------------------------
    // data SimpleXMLElement : элемент данных, в котором производится поиск
    // path           string : путь XPath
    // checkExist       bool : необходима ли проверка на существование найденных узлов
    // Возвращает параметры----------------------------------
    // SimpleXMLElement : найденный узел
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\XMLValidator :
    // code:
    //  5 - ошибка при получении XML-пути
    //  6 - не найден узел по XML-пути
    //  7 - найдено более одного узла по XML-пути
    //
    public function getUniquenessNode(SimpleXMLElement $data, string $path, bool $checkExist = true): SimpleXMLElement
    {
        $node = $data->xpath($path);

        if ($node === false) {
            throw new SelfEx("Ошибка при получении XML-пути: {$path}", 5);
        }

        if ($checkExist && empty($node)) {
            throw new SelfEx("Не найден узел по XML-пути: {$path}", 6);
        }

        if (count($node) > 1) {
            throw new SelfEx("Найдено более одного узла по XML-пути: {$path}", 7);
        }

        return array_shift($node);
    }


    // todo низкое непроверено
    // Предназначен для получения узлов XPath пути
    // Принимает параметры-----------------------------------
    // data SimpleXMLElement : элемент данных, в котором производится поиск
    // path           string : путь XPath
    // checkExist       bool : необходима ли проверка на существование найденных узлов
    // Возвращает параметры----------------------------------
    // array : массив объектов SimpleXMLElement
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\XMLValidator :
    // code:
    //  5 - ошибка при получении XML-пути
    //  6 - не найдены узлы по XML-пути
    //
    public function getNodes(SimpleXMLElement $data, string $path, bool $checkExist = true): array
    {
        $nodes = $data->xpath($path);

        if ($nodes === false) {
            throw new SelfEx("Ошибка при получении XML-пути: {$path}", 5);
        }

        if ($checkExist && empty($nodes)) {
            throw new SelfEx("Не найдены узлы по XML-пути: {$path}", 6);
        }

        return $nodes;
    }
}