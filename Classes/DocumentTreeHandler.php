<?php


namespace Classes;


/**
 * Предназначен для получения данных из массива иерархии документов, полученных методом:
 * {@see \Tables\Docs\Relations\HierarchyTree::getTree()}
 *
 */
class DocumentTreeHandler
{

    /**
     * Массив иерархии документов
     *
     */
    private array $tree;


    /**
     * Конструктор класса
     *
     * @param array $tree массив иерархии документов
     */
    public function __construct(array $tree)
    {
        $this->tree = $tree;
    }


    // cE - checkExist


    /**
     * Предназначен для проверки существования заявления
     *
     * @return bool
     */
    public function ce_application(): bool
    {
        return isset($this->tree['id']);
    }


    /**
     * Предназначен для проверки существования сводного замечания / заключения
     *
     * @return bool
     */
    public function ce_totalCC(): bool
    {
        // Так как сводное замечание / заключение может быть создано только при сохраненном виде объекта
        return isset($this->tree['id_type_of_object']) && isset($this->tree['children']['total_cc']);
    }


    /**
     * Предназначен для проверки существования раздела(ов)
     *
     * @return bool
     */
    public function ce_sections(): bool
    {
        return isset($this->tree['children']['total_cc']['children']);
    }


    /**
     * Предназначен для проверки существования конкретного раздела по его id
     *
     * @param int $sectionId id раздела
     * @return bool
     */
    public function ce_section(int $sectionId): bool
    {
        $arr = $this->tree['children']['total_cc']['children'];

        foreach ($arr as ['id' => $id]) {
            if ($sectionId == $id) {
                return true;
            }
        }
        return false;
    }


    /**
     * Предназначен для получения id заявления
     *
     * @return int
     */
    public function getApplicationId(): int
    {
        return $this->tree['id'];
    }


    /**
     * Предназначен для получения id вида объекта
     *
     * @return int
     */
    public function getTypeOfObjectId(): int
    {
        return $this->tree['id_type_of_object'];
    }


    /**
     * Предназначен для получения id сводного замечания / заключения
     *
     * @return int
     */
    public function getTotalCCId(): int
    {
        return $this->tree['children']['total_cc']['id'];
    }


    /**
     * Предназначен для получения индексного массива с разделами
     *
     * @return array индексный массив формата:<br>
     * 0 => ['id' => (int), 'children' => (array)],<br>
     * ...
     */
    public function getSections(): array
    {
        return $this->tree['children']['total_cc']['children'];
    }
}