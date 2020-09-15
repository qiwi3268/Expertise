<?php


namespace core\Classes;

use core\Classes\Exceptions\RoutesXMLHandler as SelfEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Singles\XMLValidator;
use Lib\Singles\PrimitiveValidator;
use SimpleXMLElement;


/**
 * Предназначен для обработки xml-схемы routes
 *
 */
final class RoutesXMLHandler
{

    /**
     * XPath шаблон для получения page
     *
     */
    private const XPATH_PAGE = "/routes/pages/page[@urn='%s']";

    /**
     * XPath шаблон для получения template
     *
     */
    private const XPATH_CALLBACK_TEMPLATE = "/routes/callback_templates/template[@id='%s']";


    private SimpleXMLElement $data;
    private XMLValidator $XMLValidator;
    private PrimitiveValidator $primitiveValidator;


    /**
     * RoutesXMLHandler constructor.
     * @throws SelfEx
     */
    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/routes.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML-схемы маршрутизации", 1);
        }
        $this->data = $data;
        $this->XMLValidator = new XMLValidator();
        $this->primitiveValidator = new PrimitiveValidator();
    }


    /**
     * Предназначен для получения узла page по аттрибуту urn
     *
     * @param string $urn urn страницы
     * @return SimpleXMLElement найденный узел page
     * @throws XMLValidatorEx
     */
    public function getPage(string $urn): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_PAGE, $urn);
        return $this->XMLValidator->getUniquenessNode($this->data, $path);
    }


    /**
     * Предназначен для получения узла template по аттрибуту id
     *
     * @param string $id id шаблона
     * @return SimpleXMLElement найденный узел template
     * @throws XMLValidatorEx
     */
    public function getTemplate(string $id): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_CALLBACK_TEMPLATE, $id);
        return $this->XMLValidator->getUniquenessNode($this->data, $path);
    }


    /**
     * Предназначен для валидации структуры узла page, без проверки конкретных значений
     *
     * @param SimpleXMLElement $page узел page
     * @throws XMLValidatorEx
     */
    public function validatePageStructure(SimpleXMLElement $page): void
    {
        $this->XMLValidator->validateAttributes($page, '<page />', ['urn']);
        $this->XMLValidator->validateChildren($page, '<page />', [], ['files', 'callbacks', 'callback_template']);

        foreach ($page->children() as $children_page) {

            $children_page_name = $children_page->getName();
            if ($children_page_name == 'files') {

                $this->validateFilesStructure($children_page);
            } elseif ($children_page_name == 'callbacks') {

                $this->validateCallbacksStructure($children_page);
            } else { // callback_template

                $this->validateCallbackTemplateStructure($children_page);
            }
        }
    }


    /**
     * Предназначен для валидации структуры узла files, без проверки конкретных значений
     *
     * @param SimpleXMLElement $files узел files
     * @throws XMLValidatorEx
     */
    private function validateFilesStructure(SimpleXMLElement $files): void
    {
        $this->XMLValidator->validateAttributes($files, '<files />', []);
        $this->XMLValidator->validateChildren($files, '<files />', ['dir']);

        foreach ($files->children() as $dir) {

            $this->XMLValidator->validateAttributes($dir, '<dir />', ['path', 'ext']);
            $this->XMLValidator->validateChildren($dir, '<dir />', ['file']);

            foreach ($dir->children() as $file) {

                $this->XMLValidator->validateAttributes($file, '<file />', ['name']);
                $this->XMLValidator->validateChildren($file, '<file />', []);
            }
        }
    }


    /**
     * Предназначен для валидации структуры узла callbacks, без проверки конкретных значений
     *
     * @param SimpleXMLElement $callbacks узел callbacks
     * @throws XMLValidatorEx
     */
    private function validateCallbacksStructure(SimpleXMLElement $callbacks): void
    {
        $this->XMLValidator->validateAttributes($callbacks, '<callbacks />', []);
        $this->XMLValidator->validateChildren($callbacks, '<callbacks />', ['namespace']);

        foreach ($callbacks->children() as $namespace) {

            $this->XMLValidator->validateAttributes($namespace, '<namespace />', ['name']);
            $this->XMLValidator->validateChildren($namespace, '<namespace />', ['class']);

            foreach ($namespace->children() as $class) {

                $this->XMLValidator->validateAttributes($class, '<class />', ['name', 'type']);
                $this->XMLValidator->validateChildren($class, '<class />', ['method']);

                foreach ($class->children() as $method) {

                    $this->XMLValidator->validateAttributes($method, '<method />', ['name']);
                    $this->XMLValidator->validateChildren($method, '<method />', []);
                }
            }
        }
    }


    /**
     * Предназначен для валидации структуры узла callback_template, без проверки конкретных значений
     *
     * Проверяет в том числе узел template, на который ссылается в аттрибуте id
     *
     * @param SimpleXMLElement $callback_template узел callback_template
     * @throws XMLValidatorEx
     */
    private function validateCallbackTemplateStructure(SimpleXMLElement $callback_template): void
    {
        $this->XMLValidator->validateAttributes($callback_template, '<callback_template />', ['id']);
        $this->XMLValidator->validateChildren($callback_template, '<callback_template />', []);

        $template = $this->getTemplate((string)$callback_template['id']);

        $this->XMLValidator->validateAttributes($template, '<template />', ['id']);
        $this->XMLValidator->validateChildren($template, '<template />', ['callbacks']);

        foreach ($template->children() as $callbacks) {

            $this->validateCallbacksStructure($callbacks);
        }
    }


    /**
     * Предназначен для обработки узла page
     *
     * Обработка детилится на 2 части:<br>
     * 1 - валидация значений, * при условии, что структура схемы была проверена ранее<br>
     * 2 - подключение файлов / вызов callback'ов
     *
     * @param SimpleXMLElement $page узел page
     * @throws SelfEx
     * @throws XMLValidatorEx
     */
    public function handleValidatedPageValues(SimpleXMLElement $page): void
    {
        $XMLHandler_result = [];

        foreach ($page->children() as $children_page) {

            $children_page_name = $children_page->getName();

            if ($children_page_name == 'files') {

                $this->handleFilesValues($children_page, $XMLHandler_result);
            } elseif ($children_page_name == 'callbacks') {

                $this->handleCallbacksValue($children_page, $XMLHandler_result);
            } else { // callback_template

                $this->handleCallbackTemplateValue($children_page, $XMLHandler_result);
            }
        }

        // Подключение файлов / вызов callback'ов идет из статической анонимной функции, чтобы
        // полностью очистить контекст выполнения в подключаемых модулях
        call_user_func(static function() use ($XMLHandler_result) {

            foreach ($XMLHandler_result as $XMLHandler_value) {

                if ($XMLHandler_value['type'] == 'file') {

                    require_once $XMLHandler_value['fs'];
                } else {

                    call_user_func([$XMLHandler_value['object'], $XMLHandler_value['method']]);
                }
            }
        });
    }


    /**
     * Предназначен для обработки значений узла files
     *
     * После обработки добавляет значения в результирующий массив
     *
     * @param SimpleXMLElement $files узел files
     * @param array $XMLHandler_result <i>ссылка</i> на массив результатов
     * @throws SelfEx
     */
    private function handleFilesValues(SimpleXMLElement $files, array &$XMLHandler_result): void
    {
        foreach ($files->children() as $dir) {

            $path = (string)$dir['path'];

            if (
                $path != ''
                && ($path[0] != '/' || $path[mb_strlen($path) - 1] != '/')
            ) {
                throw new SelfEx("Путь к файлу: '{$path}' должен начинться и заканчиваться на '/'", 2);
            }

            $ext = (string)$dir['ext'];

            foreach ($dir->children() as $file) {

                $name = (string)$file['name'];

                $fs = ROOT . "{$path}{$name}{$ext}";

                if (!file_exists($fs)) {
                    throw new SelfEx("Файл по пути: '{$fs}' не существует в файловой системе сервера", 3);
                }
                $XMLHandler_result[] = [
                    'type' => 'file',
                    'fs'   => $fs
                ];
            }
        }
    }


    /**
     * Предназначен для обработки значений узла callbacks
     *
     * После обработки добавляет значения в результирующий массив
     *
     * @param SimpleXMLElement $callbacks узел callbacks
     * @param array $XMLHandler_result <i>ссылка</i> на массив результатов
     * @throws SelfEx
     */
    private function handleCallbacksValue(SimpleXMLElement $callbacks, array &$XMLHandler_result): void
    {
        foreach ($callbacks->children() as $namespace) {

            $nsp = (string)$namespace['name'];

            if ($nsp[0] != '\\' || $nsp[mb_strlen($nsp) - 1] == '\\') {
                throw new SelfEx("Пространство имен: '{$nsp}' должно начинться с '\\' и не должено заканчиваться на '\\'", 4);
            }

            foreach ($namespace->children() as $class) {

                $className = (string)$class['name'];
                $fullClassName = "{$nsp}\\{$className}";

                try {
                    $this->primitiveValidator->validateClassExist($fullClassName);
                } catch (PrimitiveValidatorEx $e) {
                    throw new SelfEx("callback класс: '{$fullClassName}' не существует", 5);
                }

                $classType = (string)$class['type'];

                try {
                    $this->primitiveValidator->validateSomeInclusions($classType, 'instance', 'static');
                } catch (PrimitiveValidatorEx $e) {
                    throw new SelfEx("Тип класса: '{$classType}' должен быть 'instance' или 'static'", 6);
                }

                if ($classType == 'instance') {
                    $object = new $fullClassName();
                } else { // static
                    $object = $fullClassName;
                }

                foreach ($class->children() as $method) {

                    $methodName = (string)$method['name'];

                    try {
                        $this->primitiveValidator->validateMethodExist($fullClassName, $methodName);
                    } catch (PrimitiveValidatorEx $e) {
                        throw new SelfEx("callback метод: '{$fullClassName}::{$methodName}' не существует", 7);
                    }
                    $XMLHandler_result[] = [
                        'type'   => 'callback',
                        'object' => $object,
                        'method' => $methodName
                    ];
                }
            }
        }
    }


    /**
     * Предназначен для обработки значений узла callback_template
     *
     * Обрабатывает в том числе узел template, на который ссылается в аттрибуте id<br>
     * После обработки добавляет значения в результирующий массив
     *
     * @param SimpleXMLElement $callback_template узел callback_template
     * @param array $XMLHandler_result <i>ссылка</i> на массив результатов
     * @throws SelfEx
     * @throws XMLValidatorEx
     */
    private function handleCallbackTemplateValue(SimpleXMLElement $callback_template, array &$XMLHandler_result): void
    {
        $template = $this->getTemplate((string)$callback_template['id']);

        foreach ($template->children() as $callbacks) {

            $this->handleCallbacksValue($callbacks, $XMLHandler_result);
        }
    }
}