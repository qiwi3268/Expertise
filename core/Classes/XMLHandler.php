<?php


namespace core\Classes;

use core\Classes\Exceptions\XMLHandler as SelfEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Singles\XMLValidator;
use Lib\Singles\PrimitiveValidator;
use SimpleXMLElement;


final class XMLHandler
{

    // XPath шаблон для получения page
    private const XPATH_PAGE = "/routes/pages/page[@urn='%s']";
    // XPath шаблон для получения template
    private const XPATH_CALLBACK_TEMPLATE = "/routes/callback_templates/template[@id='%s']";


    private SimpleXMLElement $data;
    private XMLValidator $XMLValidator;
    private PrimitiveValidator $primitiveValidator;


    // Выбрасывает исключения--------------------------------
    // core\Classes\Exceptions\XMLHandler :
    // code:
    //  1  - ошибка при инициализации XML-схемы маршрутизации
    //
    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/routes.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML-схемы маршрутизации", 1);
        }
        $this->data = $data;
        $this->XMLValidator = new XMLValidator();
        $this->primitiveValidator = new PrimitiveValidator();
    }


    // Предназначен для получения узла page по аттрибуту urn
    // Принимает параметры-----------------------------------
    // urn string : urn страницы
    // Возвращает параметры----------------------------------
    // SimpleXMLElement : найденный узел page
    //
    public function getPage(string $urn): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_PAGE, $urn);
        return $this->getUniquenessNode($path);
    }


    // Предназначен для получения узла template по аттрибуту id
    // Принимает параметры-----------------------------------
    // id string : id шаблона
    // Возвращает параметры----------------------------------
    // SimpleXMLElement : найденный узел template
    //
    public function getTemplate(string $id): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_CALLBACK_TEMPLATE, $id);
        return $this->getUniquenessNode($path);
    }


    // Предназначен для получения уникального узла XPath пути
    // Принимает параметры-----------------------------------
    // path string : путь XPath
    // Выбрасывает исключения--------------------------------
    // core\Classes\Exceptions\XMLHandler :
    // code:
    //  2  - ошибка при получении XML-пути
    //  3  - не найдено узлов по XML-пути
    //  4  - найдено более одного узла по XML-пути
    //
    private function getUniquenessNode(string $path): SimpleXMLElement
    {
        $node = $this->data->xpath($path);

        if ($node === false) {
            throw new SelfEx("Ошибка при получении XML-пути: {$path}", 2);
        }

        if (empty($node)) {
            throw new SelfEx("Не найдено узлов по XML-пути: {$path}", 3);
        }

        if (count($node) > 1) {
            throw new SelfEx("Найдено более одного узла по XML-пути: {$path}", 4);
        }

        return array_shift($node);
    }



    // Предназначен для валидации структуры <page />, без проверки конкретных значений
    // Принимает параметры-----------------------------------
    // page SimpleXMLElement : нужный узел page из XML-схемы маршрутизации
    //
    public function validatePageStructure(SimpleXMLElement $page): void
    {
        $this->XMLValidator->validateAttributes($page, '<page />', ['urn']);
        $this->XMLValidator->validateChildren($page, '<page />', [], ['files', 'callbacks', 'callback_template']);


        foreach ($page->children() as $children_page) {

            $children_page_name = $children_page->getName();
            if ($children_page_name == 'files') {

                $files = &$children_page;
                $this->validateFilesStructure($files);
            } elseif ($children_page_name == 'callbacks') {

                $callbacks = &$children_page;
                $this->validateCallbacksStructure($callbacks);
            } else { //callback_template

                $callback_template = &$children_page;
                $this->validateCallbackTemplateStructure($callback_template);
            }
        }
    }


    private function validateFilesStructure(SimpleXMLElement $files): void
    {
        $this->XMLValidator->validateAttributes($files, '<files />', []);
        $this->XMLValidator->validateChildren($files, '<files />', ['dir']);

        foreach ($files->children() as $dir) {

            $this->XMLValidator->validateAttributes($dir, '<dir />', ['path', 'ext']);
            $this->XMLValidator->validateChildren($dir, '<dir />', ['file']);

            foreach ($dir->children() as $file) {

                $this->XMLValidator->validateAttributes($file, '<file/>', ['name']);
                $this->XMLValidator->validateChildren($file, '<file/>', []);
            }
        }
    }


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


    // Предназначен для обработки узла page. Обработка детилится на 2 части:
    //    1 - валидация значений, * при условии, что структура схемы была проверена ранее
    //    2 - подключение файлов / вызов callback'ов
    // Принимает параметры-----------------------------------
    // page SimpleXMLElement : нужный узел page из XML-схемы маршрутизации
    // Выбрасывает исключения--------------------------------
    // core\Classes\Exceptions\XMLHandler :
    // code:
    //  5  - путь к файлу должен начинться и заканчиваться на '/'
    //  6  - файл по пути не существует в файловой системе сервера
    //  7  - пространство имен должно начинться с '\\' и не должено заканчиваться на '\\'
    //  8  - callback класс не существует
    //  9  - тип класса должен быть 'instance' или 'static'
    //  10 - callback метод не существует"
    //
    public function handleValidatedPageValues(SimpleXMLElement $page): void
    {
        $XMLHandler_result = [];

        foreach ($page->children() as $children_page) {

            $children_page_name = $children_page->getName();

            if ($children_page_name == 'files') {

                $files = &$children_page;
                $this->handleFilesValues($files, $XMLHandler_result);
            } elseif ($children_page_name == 'callbacks') {

                $callbacks = &$children_page;
                $this->handleCallbacksValues($callbacks, $XMLHandler_result);
            } else { //callback_template

                $callback_template = &$children_page;
                $this->handleCallbackTemplateValue($callback_template, $XMLHandler_result);
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


    private function handleFilesValues(SimpleXMLElement $files, &$XMLHandler_result): void
    {
        foreach ($files->children() as $dir) {

            $path = (string)$dir['path'];

            if (
                $path != ''
                && ($path[0] != '/' || $path[mb_strlen($path) - 1] != '/')
            ) {
                throw new SelfEx("Путь к файлу: '{$path}' должен начинться и заканчиваться на '/'", 5);
            }

            $ext = (string)$dir['ext'];

            foreach ($dir->children() as $file) {

                $name = (string)$file['name'];

                $fs = ROOT . "{$path}{$name}{$ext}";

                if (!file_exists($fs)) {
                    throw new SelfEx("Файл по пути: '{$fs}' не существует в файловой системе сервера", 6);
                }
                $XMLHandler_result[] = [
                    'type' => 'file',
                    'fs'   => $fs
                ];
            }
        }
    }


    private function handleCallbacksValues(SimpleXMLElement $callbacks, &$XMLHandler_result): void
    {
        foreach ($callbacks->children() as $namespace) {

            $nsp = (string)$namespace['name'];

            if ($nsp[0] != '\\' || $nsp[mb_strlen($nsp) - 1] == '\\') {
                throw new SelfEx("Пространство имен: '{$nsp}' должно начинться с '\\' и не должено заканчиваться на '\\'", 7);
            }

            foreach ($namespace->children() as $class) {

                $className = (string)$class['name'];
                $fullClassName = "{$nsp}\\{$className}";

                try {
                    $this->primitiveValidator->validateClassExist($fullClassName);
                } catch (PrimitiveValidatorEx $e) {
                    throw new SelfEx("callback класс: '{$fullClassName}' не существует", 8);
                }

                $classType = (string)$class['type'];

                try {
                    $this->primitiveValidator->validateSomeInclusions($classType, 'instance', 'static');
                } catch (PrimitiveValidatorEx $e) {
                    throw new SelfEx("Тип класса: '{$classType}' должен быть 'instance' или 'static'", 9);
                }

                if ($classType == 'instance') {
                    $object = new $fullClassName();
                } else {
                    $object = $fullClassName;
                }

                foreach ($class->children() as $method) {

                    $methodName = (string)$method['name'];

                    try {
                        $this->primitiveValidator->validateMethodExist($fullClassName, $methodName);
                    } catch (PrimitiveValidatorEx $e) {
                        throw new SelfEx("callback метод: '{$fullClassName}::{$methodName}' не существует", 10);
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


    private function handleCallbackTemplateValue(SimpleXMLElement $callback_template, &$XMLHandler_result): void
    {
        $template = $this->getTemplate((string)$callback_template['id']);

        foreach ($template->children() as $callbacks) {

            $this->handleCallbacksValues($callbacks, $XMLHandler_result);
        }
    }
}