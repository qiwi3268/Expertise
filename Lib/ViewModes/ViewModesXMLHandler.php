<?php


namespace Lib\ViewModes;

use Lib\Exceptions\ViewModes as SelfEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;

use Lib\Singles\XMLValidator;
use Lib\Singles\PrimitiveValidator;
use SimpleXMLElement;


/**
 * Предназначен для обработки XML схемы view_modes
 *
 */
class ViewModesXMLHandler
{

    /**
     * XPath шаблон для получения document
     *
     */
    private const XPATH_DOCUMENT = "/view_modes/documents/document[@type='%s']";

    private SimpleXMLElement $data;
    private XMLValidator $XMLValidator;
    private PrimitiveValidator $primitiveValidator;


    /**
     * Конструктор класса
     *
     * @throws SelfEx
     */
    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/view_modes.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML схемы режимов просмотра", 1001);
        }
        $this->data = $data;
        $this->XMLValidator = new XMLValidator();
        $this->primitiveValidator = new PrimitiveValidator();
    }


    /**
     * Предназначен для получения узла document по аттрибуту type
     *
     * @param string $type тип документа
     * @return SimpleXMLElement найденный узел document
     * @throws XMLValidatorEx
     */
    public function getDocument(string $type): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_DOCUMENT, $type);
        return $this->XMLValidator->getUniquenessNode($this->data, $path);
    }


    /**
     * Предназначен для валидации <b>структуры</b> узла document, без проверки конкретных значений
     *
     * @param SimpleXMLElement $document узел document
     * @throws XMLValidatorEx
     */
    public function validateDocumentStructure(SimpleXMLElement $document): void
    {
        $this->XMLValidator->validateAttributes($document, '<document />', ['type', 'class']);
        $this->XMLValidator->validateChildren($document, '<document />', ['mode']);

        foreach ($document->children() as $mode) {

            $this->XMLValidator->validateAttributes($mode, '<mode />', ['name', 'label']);
            $this->XMLValidator->validateChildren($mode, '<mode />', []);
        }
    }


    /**
     * Предназначен для получения массива с обработанными <b>значениями</b> узла document
     *
     * @param SimpleXMLElement $document узел document
     * @param string $namespace Начинается на '/'. Заканчивается без '/'
     * @return array ассоциативный массив формата:<br>
     * 'class' - полное наименование класса проверки доступа к режимам просмотра<br>
     * 'modes' - индексный массив с ассоциативными массивами режимов просмотра формата:<br>
     * 'method' - наименование метода проверки доступа к режиму просмотра<br>
     * 'name' - name из XML<br>
     * 'label' - label из XML
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function getHandledModesValue(SimpleXMLElement $document, string $namespace): array
    {
        $result = [];

        $class = "{$namespace}\\{$document['class']}";

        if (!class_exists($class)) {
            throw new SelfEx("Класс проверки доступа к режимам просмотра '{$class}' не существует", 1002);
        }

        $result['class'] = $class;

        // Временные массивы для проверки уникальности аттрибутов
        $tmp_labels = [];
        $tmp_names = [];

        // Флаг существования режима просмотра по умолчанию (mode['name'] = 'view')
        $issetDefaultMode = false;

        foreach ($document->children() as $mode) {

            $name = (string)$mode['name'];
            $label = (string)$mode['label'];

            $method = snakeToCamelCase($name);

            if (!method_exists($class, $method)) {
                throw new SelfEx("Метод проверки доступа к режиму просмотра '{$class}'::'{$method}' не существует", 1003);
            }

            try {
                $this->primitiveValidator->validateReturnType([$class, $method], 'bool');
            } catch (PrimitiveValidatorEx $e) {
                throw new SelfEx("Метод проверки доступа к режиму просмотра '{$class}'::'{$method}' должен возвращать bool значение", 1004);
            }

            try {
                $this->primitiveValidator->validateNoEmptyString($label);
            } catch (PrimitiveValidatorEx $e) {
                throw new SelfEx("label режима просмотра должно быть не пустой строкой", 1005);
            }

            if (isset($tmp_names[$name])) {
                throw new SelfEx("name режима просмотра: '{$name}' не является уникальным значением", 1006);
            } elseif (isset($tmp_labels[$label])) {
                throw new SelfEx("label режима просмотра: '{$name}' не является уникальным значением", 1007);
            } else {
                $tmp_names[$name] = true;
                $tmp_labels[$label] = true;
            }

            if (!$issetDefaultMode && $name == 'view') {
                $issetDefaultMode = true;
            }

            $result['modes'][] = [
                'method' => $method,
                'name'   => $name,
                'label'  => $label
            ];
        }

        if (!$issetDefaultMode) {
            throw new SelfEx("Не объявлен режим просмотра по умолчанию с name == 'view'", 1008);
        }
        return $result;
    }
}