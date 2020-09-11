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
    // Выбрасывает исключения--------------------------------
    // core\Classes\Exceptions\XMLHandler :
    // code:
    //  2  - ошибка при получении XML-пути
    //  3  - не найдено узлов по XML-пути
    //  4  - найдено более одного узла по XML-пути
    //
    public function getPage(string $urn): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_PAGE, $urn);
        $page = $this->data->xpath($path);

        if ($page === false) {
            throw new SelfEx("Ошибка при получении XML-пути: {$path}", 2);
        }

        if (empty($page)) {
            throw new SelfEx("Не найдено узлов по XML-пути: {$path}", 3);
        }

        if (count($page) > 1) {
            throw new SelfEx("Найдено более одного узла по XML-пути: {$path}", 4);
        }

        return array_shift($page);
    }


    // Предназначен для валидации структуры <page />, без проверки конкретных значений
    // Принимает параметры-----------------------------------
    // page SimpleXMLElement : нужный узел page из XML-схемы маршрутизации
    //
    public function validatePageStructure(SimpleXMLElement $page): void
    {
        $this->XMLValidator->validateAttributes($page, '<page />', ['urn']);
        //todo важное проверить
        try {

            $this->XMLValidator->validateChildren($page, '<page />', ['files'], ['callbacks']);
        } catch (XMLValidatorEx $e) {

            $e_message = $e->getMessage();
            $e_code = $e->getCode();

            // Обрабатываем только ситуацию, когда не найден обязательный дочерний узел (files),
            // т.к. может быть page, у которой будут только callbacks без files
            if ($e_code == 3) {
                $this->XMLValidator->validateChildren($page, '<page />', ['callbacks'], ['files']);
            } else {
                throw new XMLValidatorEx($e_message, $e_code);
            }
        }

        foreach ($page->children() as $children_page) {

            if ($children_page->getName() == 'files') {

                $files = &$children_page;
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
            } else {

                $callbacks = &$children_page;
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

            if ($children_page->getName() == 'files') {

                $files = &$children_page;

                foreach ($files->children() as $dir) {

                    $path = (string)$dir['path'];

                    if ($path != ''
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
            } else {

                $callbacks = &$children_page;

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
        }

        foreach ($XMLHandler_result as $XMLHandler_value) {

            if ($XMLHandler_value['type'] == 'file') {
                require_once $XMLHandler_value['fs'];
            } else {
                call_user_func_array(
                    [
                        $XMLHandler_value['object'],
                        $XMLHandler_value['method']
                    ], []);
            }
        }
    }
}