<?php


namespace Classes\Navigation;

use Lib\Exceptions\PrimitiveValidator as  PrimitiveValidatorEx;
use Classes\Exceptions\Navigation as SelfEx;
use Lib\Singles\XMLValidator;
use Lib\Singles\PrimitiveValidator;
use SimpleXMLElement;



// Предназначен для работы с навигацией пользователя по XML схеме
//
class Navigation
{

    private XMLValidator $XMLValidator;
    private PrimitiveValidator $PrimitiveValidator;

    private const ABSTRACT_CLASS_NAME = '\Classes\Navigation\NavigationTable';
    public const NAMESPACE_CLASSES = '\Classes\Navigation\BlockClasses';
    public const VIEWS_PATH = ROOT . '/views/home/navigation';


    // Соответствие Ключ = роль => Значение = перечисление блоков из XML-схемы навигации
    private const BLOCKS = [
        ROLE['APP'] => ['block_1', 'block_2', 'block_3'],
    ];

    // Навигационный массив пользователя, аналогичный по структуре XML-схеме
    private array $userNavigation = [];


    // Выбрасывает исключения--------------------------------
    // Classes\Exceptions\Navigation :
    // code:
    //  1  - ошибка при инициализации XML-схемы навигации
    //  2  - пользователю c ролями не определен ни один навигационный блок
    //  3  - в XML-схеме навигации отсутствуют блоки
    //
    public function __construct(array $userRoles)
    {
        $this->XMLValidator = new XMLValidator();
        $this->PrimitiveValidator = new PrimitiveValidator();

        if (($data = simplexml_load_file(SETTINGS . '/navigation.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML-схемы навигации", 1);
        }


        // Валидации структуры схемы
        $this->validateNavigationXML($data);

        // Проверка уникальности значения аттрибута name
        $this->checkNameUniqueness($data);

        // todo заглушка
        $userRoles = ['APP', 'ADM', 'EXP'];

        // Собираем нужные пользователю блоки
        $requiredBlocks = [];

        foreach ($userRoles as $role) {

            if (isset(self::BLOCKS[$role])) {
                $requiredBlocks = [...$requiredBlocks, ...self::BLOCKS[$role]];
            }
        }

        if (empty($requiredBlocks)) {
            $msg = implode(', ', $userRoles);
            throw new SelfEx("Пользователю c ролями: {$msg} не определен ни один навигационный блок", 2);
        }

        // Проходим по всем блокам и берем доступные пользователю
        foreach ($data->block as $block) {

            $name = (string)$block['name'];

            foreach ($requiredBlocks as $index => $requiredName) {

                if ($requiredName == $name) {

                    $this->addBlockToNavigation($block);
                    // Удаляем блок для ускорения последующих циклов. В итоге все блоки должны быть удалены
                    unset($requiredBlocks[$index]);
                    break;
                }
            }
        }

        // Остались нужные блоки пользователю, которых нет в xml
        if (!empty($requiredBlocks)) {
            $msg = implode(', ', $requiredBlocks);
            throw new SelfEx("В XML-схеме навигации отсутствуют блоки: '{$msg}'", 3);
        }

        // Валидация значений схемы
        $this->validateUserNavigation();

        // Валидация константного массива NAVIGATION_SORTING
        $this->validateNavigationSorting();
    }


    // Предназначен для получения навигационного массива пользовалеля
    // Возвращает--------------------------------------------
    // array : навигационный массив пользователя
    //
    public function getUserNavigation(): array
    {
        return $this->userNavigation;
    }


    // Предназначен для добавления XML блока в обычный массив навигации пользователя
    // Принимает параметры-----------------------------------
    // block SimpleXMLElement : <block> из XML-схемы навигации
    //
    private function addBlockToNavigation(SimpleXMLElement $block): void
    {

        $result['name'] = (string)$block['name'];
        $result['label'] = (string)$block['label'];

        foreach ($block->view as $view) {
            $arr = (array)$view->attributes();
            $result['views'][] = $arr['@attributes'];
        }

        foreach ($block->ref as $ref) {
            $arr = (array)$ref->attributes();
            $result['refs'][] = $arr['@attributes'];
        }

        $this->userNavigation[] = $result;
    }


    // Предназначен для валидации СТРКУТУРЫ XML-схемы согласно правилам:
    // <block />
    //    name get-параметр b
    //    label имя для отображения блока
    // <view />
    //    name get-параметр v
    //    label имя для отображения строки в блоке
    //    class_name имя класса, в котором реализован интерефейс навигации
    //    view_name подключаемое к странице view
    //    show_counter флаг отображажения счетчика входящих во вью записей
    // <ref />
    //    label имя для отображения строки в блоке
    //    value ссылка для перехода на указанную страницу
    // Принимает параметры-----------------------------------
    // XML SimpleXMLElement : XML-схема навигации
    //
    private function validateNavigationXML(SimpleXMLElement $XML): void
    {
        foreach ($XML->block as $block) {

            $this->XMLValidator->validateAttributes($block, '<block />', ['name', 'label']);
            $this->XMLValidator->validateChildren($block, '<block />', [], ['view', 'ref']);

            foreach ($block->view as $view) {

                $this->XMLValidator->validateAttributes($view, '<view />', ['name', 'label', 'class_name', 'view_name', 'show_counter']);
                $this->XMLValidator->validateChildren($view, '<view />', []);
            }

            foreach ($block->ref as $ref) {

                $this->XMLValidator->validateAttributes($ref, '<ref />', ['label', 'value']);
                $this->XMLValidator->validateChildren($ref, '<ref />', []);
            }
        }
    }


    // Предназначен для проверки уникальности имен в XML схеме согласно правилам:
    // name всех <block /> должны быть уникальны
    // name всех <view /> внутри <block /> должны быть уникальны
    // Принимает параметры-----------------------------------
    // XML SimpleXMLElement : XML-схема навигации
    // Выбрасывает исключения--------------------------------
    // Classes\Exceptions\Navigation :
    // code:
    //  4  - в узле <block /> присутствуют узлы <view /> с одинаковыми атрибутами name
    //  5  - присутствуют узлы <block /> с одинаковыми аттрибутами name
    //
    private function checkNameUniqueness(SimpleXMLElement $XML): void
    {
        $blockNames = [];

        foreach ($XML->block as $block) {

            $blockName = (string)$block['name'];
            $blockNames[] = $blockName;

            $viewNames = [];
            foreach ($block->view as $view) {
                $viewNames[] = (string)$view['name'];
            }

            foreach (array_count_values($viewNames) as $name => $count) {
                if ($count > 1) throw new SelfEx("В узле <block /> name: '{$blockName}' присутствуют узлы <view /> с одинаковыми атрибутами name: '{$name}'", 4);
            }
        }

        foreach (array_count_values($blockNames) as $name => $count) {
            if ($count > 1) throw new SelfEx("Присутствуют узлы <block /> с одинаковыми аттрибутами name: '{$name}'", 5);
        }
    }
    

    // Предназначен для валидации ЗНАЧЕНИЙ навигационного массива пользователя согласно правилам:
    // <view />
    //    class_name класс, располагаемый в пакете self::NAMESPACE_CLASSES\{class_name}
    //               класс должен быть наследником абстрактного класса NavigationTable
    //    view_name view, располагаемое по пути self::VIEWS_PATH/{view_name}.php
    //    show_counter принимает значение 0 или 1
    // <ref />
    //    value внутрение ссылки должны начинаться с '/'
    //          внешние ссылки начинаюся с 'http'
    // Выбрасывает исключения--------------------------------
    // Classes\Exceptions\Navigation :
    // code:
    //  6  - абстрактный класс навигационной страницы не существует
    //  7  - требуемый класс не существует
    //  8  - файл view по пути не существует
    //  9  - аттрибут show_counter не равен 0 или 1
    //  10 - внутренняя ссылка на внутренний ресурс должна начинаться с символа '/'
    //
    private function validateUserNavigation(): void
    {

        if (!class_exists(self::ABSTRACT_CLASS_NAME)) {
            throw new SelfEx("Абстрактный класс навигационной страницы: '" . self::ABSTRACT_CLASS_NAME . "' не существует", 6);
        }

        foreach ($this->userNavigation as $block) {

            if (isset($block['views'])) {

                foreach ($block['views'] as [
                    'name'         => $name,
                    'class_name'   => $class_name,
                    'view_name'    => $view_name,
                    'show_counter' => $show_counter
                ]) {

                    $nodeName = "'{$block['name']}'->'{$name}'";

                    $class_name = self::NAMESPACE_CLASSES . "\\{$class_name}";

                    // Проверка существования класса
                    if (!class_exists($class_name)) {
                        throw new SelfEx("Требуемый класс: '{$class_name}' в узле: '{$nodeName}' не существует", 7);
                    }

                    // Валидация подключаемой view -------------------------------------------------------------
                    $view_path = self::VIEWS_PATH . "/{$view_name}.php";

                    // Проверка существования файла view
                    if (!file_exists($view_path)) {
                        throw new SelfEx("Файл view в узле: '{$nodeName}' по пути: '{$view_path}' не существует", 8);
                    }

                    // Валидация флага отображения счетчика ----------------------------------------------------
                    try {
                        $this->PrimitiveValidator->validateSomeInclusions($show_counter, '0', '1');
                    } catch (PrimitiveValidatorEx $e) {
                        throw new SelfEx("Аттрибут show_counter со значением: '{$show_counter}' в узле: '{$nodeName}' не равен 0 или 1", 9);
                    }
                }
            }

            if (isset($block['refs'])) {

                foreach ($block['refs'] as ['value' => $value]) {
                    // Валидация ссылки на указанную страницу --------------------------------------------------
                    if (!containsAll($value, 'http') && ($value[0] != '/')) {
                        throw new SelfEx("Внутренняя ссылка: '{$value}' в блоке: '{$block['label']}' на внутренний ресурс должна начинаться с символа '/'", 10);
                    }
                }
            }
        }
    }


    // Предназначен для валидации константного массива NAVIGATION_SORTING
    // Все view, имеющиеся в навигационном массиве пользователя должны быть объявлены в массиве NAVIGATION_SORTING
    //
    private function validateNavigationSorting(): void
    {
        // Формирование списка уникальных (не повторяющихся view пользователя)
        $uniqueViews = [];

        foreach ($this->userNavigation as $block) {

            if (isset($block['views'])) {

                foreach ($block['views'] as ['view_name' => $view_name]) {
                    $uniqueViews[$view_name] = $view_name;
                }
            }
        }

        // Проверка существования каждой view в константном массиве
        foreach ($uniqueViews as $view_name) {

            if (!isset(NAVIGATION_SORTING[$view_name])) {
                throw new SelfEx("В константном массиве NAVIGATION_SORTING отсутствует объявление view: '{$view_name}'");
            }
        }
    }
}