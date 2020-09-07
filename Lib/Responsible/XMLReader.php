<?php


namespace Lib\Responsible;

use Exception as SelfEx; //todo
use SimpleXMLElement;


class XMLReader
{

    private const XPATH_FLAT = "/responsible/document[@name='%s']/queries/%s";
    private const XPATH_BY_TYPE = "/responsible/document[@name='%s']/queries/%s/%s";

    private SimpleXMLElement $data;


    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/responsible.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML-схемы ответственных", 1);
        }
        $this->data = $data;
    }


    public function getResponsibleType(string $document): array
    {
        $path = sprintf(self::XPATH_FLAT, $document, 'getResponsibleType');
        return $this->getValidatedResults($path);
    }


    public function updateResponsibleType(string $document): array
    {
        $path = sprintf(self::XPATH_FLAT, $document, 'updateResponsibleType');
        return $this->getValidatedResults($path);
    }


    public function createResponsible(string $document, string $type): array
    {
        $path = sprintf(self::XPATH_BY_TYPE, $document, 'createResponsible', $type);
        return $this->getValidatedResults($path);
    }


    public function getResponsible(string $document, string $type): array
    {
        $path = sprintf(self::XPATH_BY_TYPE, $document, 'getResponsible', $type);
        return $this->getValidatedResults($path);
    }


    public function deleteResponsible(string $document, string $type): array
    {
        $path = sprintf(self::XPATH_BY_TYPE, $document, 'deleteResponsible', $type);
        return $this->getValidatedResults($path);
    }


    private function getValidatedResults(string $path): array
    {
        $XMLElement = $this->data->xpath($path);

        if (
            $XMLElement === false
            || empty($XMLElement)
            || (count($XMLElement) != 1)
            || (count($XMLElement[0]) != 2)
        ) {
            throw new SelfEx("Ошибка при получении XML-пути: '{$path}' в схеме ответственных", 2);
        }

        $XMLElement = $XMLElement[0];

        if (!class_exists($class = (string)$XMLElement->class['name'])) {
            throw new SelfEx("Класс: '{$class}' в XML-схеме ответственных не существует", 3);
        }

        if (!method_exists($class, $method = (string)$XMLElement->method['name'])) {
            throw new SelfEx("Метод: '{$class}:{$method}' в XML-схеме ответственных не существует", 4);
        }

        return [
            'class'  => $class,
            'method' => $method
        ];
    }

}