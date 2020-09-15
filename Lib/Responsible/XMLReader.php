<?php


namespace Lib\Responsible;

use Lib\Exceptions\Responsible as SelfEx;
use SimpleXMLElement;


/**
 * Предназначен для получения названий классов и методов из XML-схемы ответственных
 *
 */
class XMLReader
{

    /**
     * XPath шаблон для "плоского" пути, т.е. без type
     *
     */
    private const XPATH_FLAT = "/responsible/document[@name='%s']/queries/%s";

    /**
     * XPath шаблон для пути с type
     *
     */
    private const XPATH_BY_TYPE = "/responsible/document[@name='%s']/queries/%s/%s";

    private SimpleXMLElement $data;

    /**
     * Конструктор класса
     *
     * @throws SelfEx
     */
    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/responsible.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML-схемы ответственных", 3);
        }
        $this->data = $data;
    }


    /**
     * @param string $document тип документа, согласно константе DOCUMENT_TYPE
     * @return array
     * @throws SelfEx
     */
    public function getResponsibleType(string $document): array
    {
        $path = sprintf(self::XPATH_FLAT, $document, 'getResponsibleType');
        return $this->getValidatedResults($path);
    }


    /**
     * @param string $document тип документа, согласно константе DOCUMENT_TYPE
     * @return array
     * @throws SelfEx
     */
    public function updateResponsibleType(string $document): array
    {
        $path = sprintf(self::XPATH_FLAT, $document, 'updateResponsibleType');
        return $this->getValidatedResults($path);
    }


    /**
     * @param string $document тип документа, согласно константе DOCUMENT_TYPE
     * @param string $type вид ответственных
     * @return array
     * @throws SelfEx
     */
    public function createResponsible(string $document, string $type): array
    {
        $path = sprintf(self::XPATH_BY_TYPE, $document, 'createResponsible', $type);
        return $this->getValidatedResults($path);
    }


    /**
     * @param string $document тип документа, согласно константе DOCUMENT_TYPE
     * @param string $type вид ответственных
     * @return array
     * @throws SelfEx
     */
    public function getResponsible(string $document, string $type): array
    {
        $path = sprintf(self::XPATH_BY_TYPE, $document, 'getResponsible', $type);
        return $this->getValidatedResults($path);
    }


    /**
     * Предназначен для удаления ответственных с указанного документа
     *
     * @param string $document тип документа, согласно константе DOCUMENT_TYPE
     * @param string $type вид ответственных
     * @return array
     * @throws SelfEx
     */
    public function deleteResponsible(string $document, string $type): array
    {
        $path = sprintf(self::XPATH_BY_TYPE, $document, 'deleteResponsible', $type);
        return $this->getValidatedResults($path);
    }


    /**
     * Предназначен для получения проверенного названия класса и метода в требуемом путь в XML-схеме
     *
     * @param string $path XPath путь
     * @return array массив формата:<br>
     * 'class'  => название класса<br>
     * 'method' => название метода
     * @throws SelfEx
     */
    private function getValidatedResults(string $path): array
    {
        $XMLElement = $this->data->xpath($path);

        if (
            $XMLElement === false
            || empty($XMLElement)
            || (count($XMLElement) != 1)
            || (count($XMLElement[0]) != 2)
        ) {
            throw new SelfEx("Ошибка при получении XML-пути: '{$path}' в схеме ответственных", 4);
        }

        $XMLElement = $XMLElement[0];

        if (!class_exists($class = (string)$XMLElement->class['name'])) {
            throw new SelfEx("Класс: '{$class}' в XML-схеме ответственных не существует", 5);
        }

        if (!method_exists($class, $method = (string)$XMLElement->method['name'])) {
            throw new SelfEx("Метод: '{$class}:{$method}' в XML-схеме ответственных не существует", 6);
        }

        return [
            'class'  => $class,
            'method' => $method
        ];
    }
}