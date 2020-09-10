<?php


namespace core\Classes;

use Exception as SelfEx; //todo
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Lib\Singles\XMLValidator;
use Lib\Singles\PrimitiveValidator;
use SimpleXMLElement;
use ReflectionClass;


class XMLHandler
{

    // XPath шаблон для получения детей page
    private const XPATH_PAGE_CHILD = "/routes/pages/page[@urn='%s']";

    private SimpleXMLElement $data;
    private XMLValidator $XMLValidator;
    private PrimitiveValidator $primitiveValidator;

    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/routes.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML-схемы маршрутизации", 1);
        }
        $this->data = $data;
        $this->validator = new XMLValidator();
        $this->primitiveValidator = new PrimitiveValidator();
    }


    // Предназначен для валидации структуры <pages />, без проверки конкретных значений
    public function validatePagesStructure(): void
    {
        // Опускаем проверки на количество блоков pages и берем только первый
        $pages = $this->data->pages[0];

        $this->validator->validateAttributes($pages, '<pages />', []);
        $this->validator->validateChildren($pages, '<pages />', ['page']);

        foreach ($pages->children() as $page) {

            $this->validator->validateAttributes($page, '<page />', ['urn']);
            $this->validator->validateChildren($page, '<page />', ['files'], ['callbacks']);

            foreach ($page->children() as $children_page) {

                if ($children_page->getName() == 'files') {

                    $files = &$children_page;
                    $this->validator->validateAttributes($files, '<files />', []);
                    $this->validator->validateChildren($files, '<files />', ['dir']);

                    foreach ($files->children() as $dir) {

                        $this->validator->validateAttributes($dir, '<dir />', ['path', 'ext']);
                        $this->validator->validateChildren($dir, '<dir />', ['file']);

                        foreach ($dir->children() as $file) {

                            $this->validator->validateAttributes($file, '<file/>', ['name']);
                            $this->validator->validateChildren($file, '<file/>', []);
                        }
                    }

                } elseif ($children_page->getName() == 'callbacks') {

                    $callbacks = &$children_page;
                    $this->validator->validateAttributes($callbacks, '<callbacks />', []);
                    $this->validator->validateChildren($callbacks, '<callbacks />', ['namespace']);

                    foreach ($callbacks->children() as $namespace) {

                        $this->validator->validateAttributes($namespace, '<namespace />', ['name']);
                        $this->validator->validateChildren($namespace, '<namespace />', ['class']);

                        foreach ($namespace->children() as $class) {

                            $this->validator->validateAttributes($class, '<class />', ['name', 'type']);
                            $this->validator->validateChildren($class, '<class />', ['method']);

                            foreach ($class->children() as $method) {

                                $this->validator->validateAttributes($method, '<method />', ['name']);
                                $this->validator->validateChildren($method, '<method />', []);
                            }
                        }
                    }
                }
            }
        }
    }


    public function getPage(string $urn): SimpleXMLElement
    {
        $path = sprintf(self::XPATH_PAGE_CHILD, $urn);
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


    public function handleValidatedPageValues(SimpleXMLElement $page): void
    {
        $result = [];

        foreach ($page->children() as $children_page) {

            if ($children_page->getName() == 'files') {

                $files = &$children_page;

                foreach ($files->children() as $dir) {

                    $path = (string)$dir['path'];
                    $ext = (string)$dir['ext'];

                    foreach ($dir->children() as $file) {

                        $name = (string)$file['name'];

                        $fs = ROOT . "{$path}{$name}{$ext}";

                        if (!file_exists($fs)) {
                            throw new SelfEx("", 5);
                        }
                        $result[] = [
                            'type' => 'file',
                            'fs'   => $fs
                        ];
                    }
                }
            } else {

                $callbacks = &$children_page;

                foreach ($callbacks->children() as $namespace) {

                    $nsp = (string)$namespace['name'];
                    
                    foreach ($namespace->children() as $class) {
                        
                        $className = (string)$class['name'];
                        $fullClassName = "{$nsp}\\{$className}";

                        try {
                            $this->primitiveValidator->validateClassExist($fullClassName);
                        } catch (PrimitiveValidatorEx $e) {
                            throw new SelfEx("Класс не существует", 6);
                        }

                        $classType = (string)$class['type'];

                        try {
                            $this->primitiveValidator->validateSomeInclusions($classType, 'instance', 'static');
                        } catch (PrimitiveValidatorEx $e) {
                            throw new SelfEx("", 7);
                        }


                        if ($classType == 'instance') {
                            
                            $reflectionClass = new ReflectionClass($fullClassName);
                            $object = $reflectionClass->newInstanceArgs();
                        } else {
                            
                            $object = $fullClassName;
                        }

                        foreach ($class->children() as $method) {

                            $methodName = (string)$method['name'];

                            try {
                                $this->primitiveValidator->validateMethodExist($fullClassName, $methodName);
                            } catch (PrimitiveValidatorEx $e) {
                                throw new SelfEx("Метод не существует", 8);
                            }
                            $result[] = ['type'   => 'callback',
                                         'object' => $object,
                                         'method' => $methodName
                            ];
                        }
                    }
                }
            }
        }

        var_dump($result);

        foreach ($result as $value) {

            ($value['type'] == 'file') ? require_once $value['fs'] : call_user_func([$value['object'], $value['method']]);
        }
    }

}