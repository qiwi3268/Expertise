<?php


$structure1 = structure_documentation1Table::getAllActive();

// Служебная проверка на то, что у каждого дочернего узла существует родитель
foreach($structure1 as $node){

    $parentNodeId = $node['id_parent_node'];

    if(!is_null($parentNodeId)){

        $parent = array_filter($structure1, fn($localNode) => ($localNode['id_node'] == $parentNodeId));

        if(empty($parent)){
            exit('ОШИБКА. У узла id: '.$node['id_node'].' отсутствует родительский узел id: '.$parentNodeId);
        }
    }
}

$structure1TV = [];

foreach($structure1 as $node){

    $depth = 0;  // Уровень вложенности узла
    $parentNodeId = $node['id_parent_node'];

    if(!is_null($parentNodeId)){

        $depth++;
        $issetParent = true;

        // Находим количество родительских узлов
        do{

            // Берем всю структуру и находим родительский узел. Берем соответственно 1 элемент
            // В теории можно вырезать дочерние элементы на каждой итерации, чтобы следующие родительские искались быстрее,
            // НО, я крайне не уверен, что лишние вызовы функций обойдутся дешевле, чем пробежать массив на 2-3 лишних элемента.
            $parent = array_filter($structure1, fn($localNode) => ($localNode['id_node'] == $parentNodeId));
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

    $structure1TV[] = ['id'    => $node['id_node'],
                       'name'  => $node['name'],
                       'depth' => $depth
                      ];
}