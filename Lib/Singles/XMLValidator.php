<?php


namespace Lib\Singles;
use Exception as SelfEx; //todo
use SimpleXMLElement;


class XMLValidator
{

    public function validateAttributes(SimpleXMLElement $node, string $debugName, bool $onlyRequired, string ...$requiredAttributes): void
    {

        // Получение массива аттрибутов узла из XML-объекта
        $xml_attributes = $node->attributes();
        $arr_attributes = (array)$xml_attributes;
        $attributes = $arr_attributes['@attributes'];

        // Названия аттрибутов
        $attributes = array_keys($attributes);
        // Строка для дампа ошибок берется сейчас, т.к. в цикле удаляются элементы массива
        $string_attributes = implode(', ', $attributes);

        $entryCount = 0;                       // Счетчик требуемых аттрибутов среди имеющихся в узле
        $countAttributes = count($attributes); // Счетчик аттрибутов в узле

        foreach ($requiredAttributes as $requiredAttribute) {

            $entryFlag = false; // Флаг того, что требуемый аттрибут присутствует среди узловых

            foreach ($attributes as $index => $nodeAttribute) {

                if ($requiredAttribute == $nodeAttribute) {

                    $entryCount++;
                    $entryFlag = true;
                    unset($attributes[$index]);
                    break;
                }
            }

            if (!$entryFlag) {
                throw new SelfEx("В узле: '{$debugName}' среди аттрибутов: '{$string_attributes}' не найден обязательный аттрибут '{$requiredAttribute}'", 1);
            }
        }

        if ($onlyRequired && ($entryCount != $countAttributes)) {
            $msg = implode(', ', $requiredAttributes);
            throw new SelfEx("В узле: '{$debugName}' имеются аттрибуты помимо: '{$msg}'", 2);
        }
    }
}