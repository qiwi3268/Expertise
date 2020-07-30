<?php


if(!checkParamsGET('b', 'v')){
    throw new Exception('Отсутствуют необходимые GET параметры');
}

$G_block = $_GET['b'];
$G_view = $_GET['v'];





$Navigation = new Navigation(Session::getUserRoles());



// Предназначен для работы с навигацией пользователя по XML схеме
//
class Navigation{
    
    // Соответствие Ключ = роль => Значение = перечисление блоков из XML-схемы навигации
    private const BLOCKS = [_ROLE['APP'] => ['block_2', 'block_3'],
    
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
        
        var_dump($this->userNavigation);
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

    
    // Предназначен для валидации XML-схемы согласно принятым правилам:
    // <block />
    //    name get-параметр b
    //    label имя для отображения блока
    // <view />
    //    name get-параметр v
    //    label имя для отображения строки в блоке
    //    class_name имя класса, в котором реализован интерефейс навигации
    //    view_name подключаемое к странице view, располагаемое по пути _ROOT_./views/home/navigation/{view_name}
    //    show_counter {0/1} флаг отображажения счетчика входящих во вью записей
    // <ref />
    //    label имя для отображения строки в блоке
    //    value ссылка для перехода на указанную страницу
    // Принимает параметры-----------------------------------
    // XML SimpleXMLElement : XML-схема навигации
    //
    public function validateNavigationXML(SimpleXMLElement $XML):void {
        
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
                $this->validateAttributes($ref, false,'<ref />','label', 'value');
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
}