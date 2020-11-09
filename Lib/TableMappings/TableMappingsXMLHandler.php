<?php


namespace Lib\TableMappings;

use Lib\Exceptions\TableMappings as SelfEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;

use SimpleXMLElement;
use Lib\Singles\XMLValidator;


/**
 * Предназначен для обработки XML схемы table_mappings
 *
 */
class TableMappingsXMLHandler
{

    private const FILE_NEEDED_INTERFACE = 'Tables\Files\Interfaces\FileTable';
    private const FILE_EXISTENT_INTERFACE = 'Tables\CommonInterfaces\Existent';
    private const SIGN_NEEDED_INTERFACE = 'Tables\Signs\Interfaces\SignTable';

    /**
     * XPath шаблон для получения mapping level 2
     *
     */
    private const XPATH_MAPPING_LEVEL_2 = "/mappings/level_1[@val='%s']/level_2[@val='%s']";

    private SimpleXMLElement $data;
    private XMLValidator $XMLValidator;


    /**
     * Конструктор класса
     *
     * @throws SelfEx
     */
    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/table_mappings.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML схемы табличных маппингов", 1001);
        }

        $this->data = $data;
        $this->XMLValidator = new XMLValidator();
    }


    /**
     * Предназначен для получения узла level_2 по атрибутам mapping_level_1 и 2
     *
     * @param int $ml_1 mapping level 1
     * @param int $ml_2 mapping level 2
     * @return SimpleXMLElement
     * @throws XMLValidatorEx
     */
    public function getLevel2(int $ml_1, int $ml_2): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_MAPPING_LEVEL_2, $ml_1, $ml_2);
        return $this->XMLValidator->getUniquenessNode($this->data, $path);
    }


    /**
     * Предназначен для валидации <b>структуры</b> узла level_2, без проверки конкретных значений
     *
     * @param SimpleXMLElement $level_2 узел level_2
     * @return $this
     * @throws XMLValidatorEx
     */
    public function validateLevel2Structure(SimpleXMLElement $level_2): self
    {
        $this->XMLValidator->validateAttributes($level_2, '<level_2 />', ['val']);
        $this->XMLValidator->validateChildren($level_2, '<level_2 />', ['file_table'], ['sign_table']);
        $this->XMLValidator->validateChildrenUniqueness($level_2, '<level_2 />', 'file_table');
        $this->XMLValidator->validateChildrenUniqueness($level_2, '<level_2 />', 'sign_table');

        // Цикл из 1 или 2х итераций
        foreach ($level_2->children() as $children) {

            if ($children->getName() == 'file_table') {

                $this->XMLValidator->validateAttributes($children, '<file_table />', ['class', 'main_document', 'uploader_class']);
                $this->XMLValidator->validateChildren($children, '<file_table />', []);
            } else { // sign_table

                $this->XMLValidator->validateAttributes($children, '<sign_table />', ['class']);
                $this->XMLValidator->validateChildren($children, '<sign_table />', []);
            }
        }
        return $this;
    }


    /**
     * Предназначен для получения массива с обработанными <b>значениями</b> узла level_2
     *
     * @param SimpleXMLElement $level_2 узел level_2
     * @return array ассоциативный массив формата:<br>
     * 'file_table_class' - полное наименование класса файловой таблицы<br>
     * 'sign_table_class' (string|null) - полное наименование класса таблицы подписей, если
     * он определен для текущего маппинга, null - в противном случае<br>
     * 'main_document_type' - тип главного документа, к которому относится файловая таблица
     * @throws SelfEx
     */
    public function getHandledLevel2Value(SimpleXMLElement $level_2): array
    {
        $result['sign_table_class'] = null;

        // Цикл из 1 или 2х итераций
        foreach ($level_2->children() as $children) {

            $class = (string)$children['class'];

            if ($children->getName() == 'file_table') {

                if (!class_exists($class)) {
                    throw new SelfEx("Класс таблицы файлов '{$class}' не существует", 1002);
                }

                if (
                    !($interfaces = class_implements($class))
                    || !in_array(self::FILE_NEEDED_INTERFACE, $interfaces)
                    || !in_array(self::FILE_EXISTENT_INTERFACE, $interfaces)
                ) {
                    $debug = "'" . self::FILE_NEEDED_INTERFACE . "' или '" . self::FILE_EXISTENT_INTERFACE . "'";
                    throw new SelfEx("Класс таблицы файлов '{$class}' не реализует интерфейс: {$debug}", 1003);
                }

                $main_document = (string)$children['main_document'];

                if (!isset(DOCUMENT_TYPE[$main_document])) {
                    throw new SelfEx("Тип документа: '{$main_document}' не определен в константе DOCUMENT_TYPE", 1004);
                }

                $result['file_table_class'] = $class;
                $result['main_document_type'] = $main_document;
            } else { // sign_table

                if (!class_exists($class)) {
                    throw new SelfEx("Класс таблицы подписей '{$class}' не существует", 1005);
                }

                if (
                    !($interfaces = class_implements($class))
                    || !in_array(self::SIGN_NEEDED_INTERFACE, $interfaces)
                ) {
                    throw new SelfEx("Класс таблицы подписей '{$class}' не реализует интерфейс: '" . self::SIGN_NEEDED_INTERFACE . "'", 1006);
                }
                $result['sign_table_class'] = $class;
            }
        }
        return $result;
    }
}