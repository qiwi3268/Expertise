<?php


// Предназначен для работы с навигацией пользователя по XML схеме
//
class Navigation{
    
    // Соответствие Ключ = роль => Значение = перечисление блоков из XML-схемы навигации
    public const BLOCKS = [_ROLE['APP'] => ['block_1'],
    
    ];
    
    // Навигационный массив пользователя, аналогичный по структуре XML-схеме
    private array $userNavigation = [];
    
    function __construct(array $userRoles){
        
        $data = simplexml_load_file(_NAVIGATION_SETTINGS);
        
        // Ошибка при инициализации объекта
        if($data === false){
            throw new Exception("Ошибка при инициализации XML-схемы навигации");
        }
        
        // Валидации схемы
        $this->validateNavigationXML($data);
        
        //todo
        $userRoles = ['APP', 'ADM', 'EXP'];
        
        // Собираем нужные пользователю блоки
        $requiredBlocks = [];
        
        foreach($userRoles as $role){
            
            // Есть соответствующий блок для роли пользователя
            if(isset(self::BLOCKS[$role])){
                $requiredBlocks = [...$requiredBlocks, ...self::BLOCKS[$role]];
            }
        }
        
        if(empty($requiredBlocks)){
            $msg = implode(', ', $userRoles);
            throw new Exception("Пользователю c ролями: $msg не определен ни один навигационный блок");
        }
        
        // Проходим по всем блокам и берем доступные пользователю
        foreach($data->block as $block){
            
            $name = (string)$block['name'];
            
            foreach($requiredBlocks as $index => $requiredName){
                
                if($requiredName == $name){
                    
                    $this->addBlockToNavigation($block);
                    // Удаляем блок для ускорения последующих циклов. В итоге все блоки должны быть удалены
                    unset($requiredBlocks[$index]);
                    break;
                }
            }
        }
        
        // Остались нужные блоки пользователю, которых нет в xml
        if(!empty($requiredBlocks)){
            $msg = implode(', ', $requiredBlocks);
            throw new Exception("В XML-схеме навигации отсутствуют блоки: $msg");
        }
        
        $this->validateUserNavigation();
    }
    
    
    // Предназначен для получения навигационного массива пользовалеля
    // Возвращает--------------------------------------------
    // array : навигационный массив пользователя
    //
    public function getUserNavigation():array {
        return $this->userNavigation;
    }
    
    
    // Предназначен для добавления XML блока в обычный массив навигации пользователя
    // Принимает параметры-----------------------------------
    // block SimpleXMLElement : <block> из XML-схемы навигации
    //
    private function addBlockToNavigation(SimpleXMLElement $block):void {
        
        $result['name'] = (string)$block['name'];
        $result['label'] = (string)$block['label'];
        
        foreach($block->view as $view){
            $arr = (array)$view->attributes();
            $result['views'][] = $arr['@attributes'];
        }
        
        foreach($block->ref as $ref){
            $arr = (array)$ref->attributes();
            $result['refs'][] = $arr['@attributes'];
        }
        
        $this->userNavigation[] = $result;
    }
    
    
    // Предназначен для валидации СТРКУТУРЫ XML-схемы согласно принятым правилам:
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
    private function validateNavigationXML(SimpleXMLElement $XML):void {
        
        $blockCount = 0;
        
        foreach($XML->block as $block){
            
            $viewCount = 0;
            $refCount = 0;
            
            $this->validateAttributes($block, true,'<block>','name', 'label');
            $blockCount++;
            
            foreach($block->view as $view){
                $this->validateAttributes($view, true,'<view />','name', 'label', 'class_name', 'view_name', 'show_counter');
                $viewCount++;
            }
            
            foreach($block->ref as $ref){
                $this->validateAttributes($ref, true,'<ref />','label', 'value');
                $refCount++;
            }
            
            if(($viewCount + $refCount) < $block->count()){
                throw new Exception("В узле <block /> name='{$block['name']}' присутствуют дочерние элементы помимо <view /> и <ref />");
            }
        }
        
        // В схеме имеются элементы кроме <block />
        if($blockCount < $XML->count()){
            throw new Exception('В XML-схеме навигации присутствуют узлы помимо <block>');
        }
    }
    
    
    // Предназначен для проверки наличия обязательных аттрибутов в узле
    // Принимает параметры-----------------------------------
    // node     SimpleXMLElement : проверяемый узел
    // onlyRequired         bool : true - вызовет исключение, если в узле имеются аттрибуты помимо требуемых
    // debugName          string : имя узла для его вывода в дамп ошибки
    // requiredAttributes string : перечисление требуемых аттрибутов
    //
    private function validateAttributes(SimpleXMLElement $node, bool $onlyRequired, string $debugName, string ...$requiredAttributes):void {
        
        // Получение массива аттрибутов узла из XML-объекта
        $tmp = (array)$node->attributes();
        $nodeAttributes = $tmp['@attributes'];
        
        // Берем только названия аттрибутов
        $nodeAttributes = array_keys($nodeAttributes);
        // Строка для дампа ошибок берется сейчас, т.к. в цикле удаляются элементы массива
        $string_nodeAttributes = implode(', ', $nodeAttributes);
        
        $entryCount = 0; // Счетчик требуемых аттрибутов среди имеющихся в узле
        $countNodeAttributes = count($nodeAttributes); // Счетчик аттрибутов в узле
        
        foreach($requiredAttributes as $requiredAttribute){
            
            $entryFlag = false; // Флаг того, что требуемый аттрибут присутствует среди узловых
            
            foreach($nodeAttributes as $index => $nodeAttribute){
                
                if($requiredAttribute == $nodeAttribute){
                    
                    $entryCount++;
                    $entryFlag = true;
                    unset($nodeAttributes[$index]);
                    break;
                }
            }
            
            if(!$entryFlag){
                throw new Exception("В узле $debugName среди аттрибутов: '{$string_nodeAttributes}' не найден обязательный аттрибут '{$requiredAttribute}'");
            }
        }
        
        if($onlyRequired && ($entryCount != $countNodeAttributes)){
            $msg = implode(', ', $requiredAttributes);
            throw new Exception("В узле $debugName имеются аттрибуты помимо: $msg");
        }
    }
    
    
    // Предназначен для валидации ЗНАЧЕНИЙ навигационного массива пользователя согласно принятым правилам:
    // *** Метод в ходе своей работы подключает все необходимые для навигации классы
    // <view />
    //    class_name класс, располагаемый по пути _ROOT_./Classes/Navigation/{class_name}.php
    //               класс должен быть наследником абстрактного класса NavigationTable
    //    view_name view, располагаемое по пути _ROOT_./views/home/navigation/{view_name}.php
    //    show_counter принимает значение 0 или 1
    // <ref />
    //    value внутрение ссылки должны начинаться с '/'
    //          внешние ссылки начинаюся с 'http'
    //
    private function validateUserNavigation():void {
        
        // Подключение абстрактного класса
        $abstract_class_name = 'NavigationTable';
        $abstract_class_path = _ROOT_."/Classes/Navigation/{$abstract_class_name}.php";
        
        if(!file_exists($abstract_class_path)){
            throw new Exception("Файл абстрактного класса навигационной страницы по пути: '{$abstract_class_path}' не существует");
        }
        require_once $abstract_class_path;
    
        if(!class_exists($abstract_class_name)){
            throw new Exception("Абстрактный класс навигационной страницы: '{$abstract_class_name}' не существует");
        }
        
        foreach($this->userNavigation as $block){
            
            foreach($block['views'] as ['name' => $name, 'class_name' => $class_name, 'view_name' => $view_name, 'show_counter' => $show_counter]){
                
                $nodeName = "'{$block['name']}'->'{$name}'";
                
                // Валидация подключаемого класса ----------------------------------------------------------
                $class_path = _ROOT_."/Classes/Navigation/{$class_name}.php";
                
                // Проверка существования файла класса
                if(!file_exists($class_path)){
                    throw new Exception("Файл класса в узле: $nodeName по пути: '$class_path' не существует");
                }
                require_once $class_path;
                
                // Проверка существования класса
                if(!class_exists($class_name)){
                    throw new Exception("Класс: '{$class_name}' в узле: $nodeName не существует");
                }
                
                $parents = class_parents($class_name, false);
                
                if(!isset($parents[$abstract_class_name])){
                    throw new Exception("Класс: '{$class_name}' в узле: $nodeName не наследуется от абстрактного класса: '{$abstract_class_name}'");
                }
    
                
                // Валидация подключаемой view -------------------------------------------------------------
                $view_path = _ROOT_."/views/home/navigation/{$view_name}.php";
                // Проверка существования файла view
                if(!file_exists($view_path)){
                    throw new Exception("Файл view в узле: $nodeName по пути: '$view_path' не существует");
                }
    
                
                // Валидация флага отображения счетчика ----------------------------------------------------
                $show_counter_valid = is_numeric($show_counter) && (($show_counter == 0) || ($show_counter == 1));
                if(!$show_counter_valid){
                    throw new Exception("Аттрибут show_counter со значением: '{$show_counter}' в узле: $nodeName не равен 0 или 1");
                }
            }
            
            if(!isset($block['refs'])) continue;
            
            foreach($block['refs'] as ['value' => $value]){

                // Валидация ссылки на указанную страницу --------------------------------------------------
                if((mb_strpos($value, 'http') === false && ($value[0] != '/'))){
                    throw new Exception("Внутренняя ссылка: '{$value}' в блоке: '{$block['label']}' на внутренний ресурс должна начинаться с символа '/'");
                }
            }
        }
    }
}