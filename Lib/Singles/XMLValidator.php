<?php


namespace Lib\Singles;

use Exception as SelfEx; //todo
use SimpleXMLElement;


class XMLValidator
{

    public function validateAttributes(SimpleXMLElement $node, string $debugName, array $requiredAttributes, array $optionalAttributes = []): void
    {
        // Получение массива аттрибутов узла из XML-объекта
        $arr_attributes = (array)$node->attributes();
        $attributes = isset($arr_attributes['@attributes']) ? array_keys($arr_attributes['@attributes']) : [];

        // Строка для дампа ошибок
        $dump_1 = implode(', ', $attributes);
        $dump_2 = implode(', ', [...$requiredAttributes, ...$optionalAttributes]);

        $attributes = getHashArray($attributes);

        foreach ($requiredAttributes as $requiredAttribute) {

            if (!isset($attributes[$requiredAttribute])) {
                throw new SelfEx("В узле: '{$debugName}' среди аттрибутов: '{$dump_1}' не найден обязательный аттрибут '{$requiredAttribute}'", 1);
            }
        }

        $entryCount = count($requiredAttributes);

        foreach ($optionalAttributes as $optionalAttribute) {
            if (isset($attributes[$optionalAttribute])) {
                $entryCount++;
            }
        }

        if (count($attributes) != $entryCount) {
            throw new SelfEx("В узле: '{$debugName}' имеются аттрибуты помимо: '{$dump_2}'", 2);
        }
    }


    public function validateChildren(SimpleXMLElement $node, string $debugName, array $requiredChildren, array $optionalChildren = [])
    {
        // Берем только названия дочерних узлов (они имеют тип SimpleXMLElement, в отличие от аттрибутов)
        $childrenName = array_filter(array_keys((array)$node), fn($el) => ($el != '@attributes'));

        $dump_1 = implode(', ', $childrenName);
        $dump_2 = implode(', ', [...$requiredChildren, ...$optionalChildren]);

        $childrenName = getHashArray($childrenName);

        foreach ($requiredChildren as $requiredName) {
            if (!isset($childrenName[$requiredName])) {
                throw new SelfEx("В узле: '{$debugName}' среди дочерних узлов: '{$dump_1}' не найден обязательный узел '{$requiredName}'", 3);
            }
        }

        $entryCount = count($requiredChildren);

        foreach ($optionalChildren as $optionalName) {
            if (isset($childrenName[$optionalName])) {
                $entryCount++;
            }
        }

        if (count($childrenName) != $entryCount) {
            throw new SelfEx("В узле: '{$debugName}' имеются дочерние узлы помимо: '{$dump_2}'", 4);
        }
    }
}