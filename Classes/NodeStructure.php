<?php

// Класс предназначен для построения структуры вложенности узлов
//
class NodeStructure{
    
    private array $structure;
    
    // Служебная проверка на то, что у каждого дочернего узла существует родитель
    function __construct(array $structure){
        
        foreach($structure as $node){
            
            $parentNodeId = $node['id_parent_node'];
            
            if(!is_null($parentNodeId)){
                
                $parent = array_filter($structure, fn($localNode) => ($localNode['id'] == $parentNodeId));
                
                if(empty($parent)){
                    throw new FileException("У узла id: {$node['id']} отсутствует родительский узел id: {$parentNodeId}");
                }
            }
        }
        
        $this->structure = $structure;
    }
    

    // Предназначен для получения "глубинной" структуры, т.е. у каждого узла будет проставлен уровень его вложенности
    // Возвращает параметры----------------------------------
    // array : массив с массивами формата id, name, depth
    //
    //
    public function getDepthStructure():array {
        
        $result = [];
    
        foreach($this->structure as $node){
        
            $depth = 0;  // Уровень вложенности узла
            $parentNodeId = $node['id_parent_node'];
        
            if(!is_null($parentNodeId)){
            
                $depth++;
                $issetParent = true;
            
                // Находим количество родительских узлов
                do{
                
                    // Берем всю структуру и находим родительский узел. Берем соответственно 1 элемент, т.к. array_filter возвращает массив с массивами
                    $parent = array_filter($this->structure, fn($localNode) => ($localNode['id'] == $parentNodeId));
                    $parent = array_shift($parent);
                
                    $parentNodeId = $parent['id_parent_node'];
                
                    if(!is_null($parentNodeId)){
                        // У родительского узла тоже есть родительский узел
                        $depth++;
                    }else{
                        $issetParent = false;
                    }
                }while($issetParent);
            }
    
            $result[] = ['id'    => $node['id'],
                         'name'  => $node['name'],
                         'depth' => $depth
            ];
        }
        return $result;
    }
}
