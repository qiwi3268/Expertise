<?php


namespace Lib\Singles;

use Lib\Exceptions\NodeStructure as SelfEx;


/**
 * Предназначен для построения структуры вложенности узлов
 *
 */
class NodeStructure
{

    private array $structure;


    /**
     * Конструктор класса
     *
     * Служебная проверка на то, что у каждого дочернего узла существует родитель
     *
     * @param array $structure структура родительских и дочерних узлов
     * @throws SelfEx
     */
    public function __construct(array $structure)
    {
        foreach ($structure as $node) {

            $parentNodeId = $node['id_parent_node'];

            if (!is_null($parentNodeId)) {

                $parent = array_filter($structure, fn($localNode) => ($localNode['id'] == $parentNodeId));

                if (empty($parent)) {
                    throw new SelfEx("У узла id: '{$node['id']}' отсутствует родительский узел id: '{$parentNodeId}'", 1);
                }
            }
        }

        $this->structure = $structure;
    }


    /**
     * Предназначен для получения "глубинной" структуры
     *
     * У каждого узла будет проставлен уровень его вложенности в свойстве 'depth'
     *
     */
    public function getDepthStructure(): array
    {
        $result = [];

        foreach ($this->structure as $node) {

            $depth = 0;  // Уровень вложенности узла
            $parentNodeId = $node['id_parent_node'];

            if (!is_null($parentNodeId)) {

                $depth++;
                $issetParent = true;

                // Находим количество родительских узлов
                do {

                    // Берем всю структуру и находим родительский узел. Берем соответственно 1 элемент, т.к. array_filter возвращает массив с массивами
                    $parent = array_filter($this->structure, fn($localNode) => ($localNode['id'] == $parentNodeId));
                    $parent = array_shift($parent);

                    $parentNodeId = $parent['id_parent_node'];

                    if (!is_null($parentNodeId)) {
                        // У родительского узла тоже есть родительский узел
                        $depth++;
                    } else {
                        $issetParent = false;
                    }
                } while ($issetParent);
            }

            $handleNode = [];

            $handleNode['depth'] = $depth;

            foreach ($node as $key => $value) {

                $handleNode[$key] = contains($key, 'is_') ? (bool)$value : $value;
            }
            $result[] = $handleNode;
        }
        return $result;
    }


    /**
     * Предназначен для получения индексного массива (нумерация с 0 индекса), в котором указаны id
     * родительских узлов
     *
     * id родительских узлов расположены по убыванию, то есть стремятся к самому верхнему узлу
     *
     * @param int $nodeId id узла
     * @param array $depthStructure "глубинная" структура.<br>
     * <b>***</b> Предполагается, что все указанные родительские узлы по 'id_parent_node' существуют
     * в принятом массиве
     * @return array
     */
    public function getNodeParents(int $nodeId, array $depthStructure): array
    {
        // Текущий узел, далее его родительские узлы и т.д.
        $currentNodeIndex = getFirstArrayEntryIndex($depthStructure, 'id', $nodeId);

        $parentCount = $depthStructure[$currentNodeIndex]['depth'];

        $parents = [];

        for ($l = 0; $l < $parentCount; $l++) {

            $currentNodeIndex = getFirstArrayEntryIndex($depthStructure, 'id', $depthStructure[$currentNodeIndex]['id_parent_node']);
            $parents[$l] = $depthStructure[$currentNodeIndex]['id'];
        }
        return $parents;
    }
}
