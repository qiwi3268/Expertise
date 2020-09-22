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
     * У каждого узла будет проставлен уровень его вложенности
     *
     * @return array массив с массивами формата id, name, is_header, depth
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

            // todo важное
            @$result[] = [
                'id'                => $node['id'],
                'id_main_block_341' => $node['id_main_block_341'],
                'name'              => $node['name'],
                'is_header'         => (bool)$node['is_header'],
                'depth'             => $depth
            ];
        }
        return $result;
    }
}
