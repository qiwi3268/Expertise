<?php


namespace Classes;


//
class DocumentTreeHandler
{

    /**
     * Массив иерархии документов
     *
     */
    private array $tree;


    public function __construct(array $tree)
    {
        $this->tree = $tree;
    }

    // cE - checkExist

    public function ce_application(): bool
    {
        return isset($this->tree['id']) && isset($this->tree['id_type_of_object']);
    }

    public function ce_totalCC(): bool
    {
        return isset($this->tree['children']['total_cc']);
    }

    public function ce_sections(): bool
    {
        return isset($this->tree['children']['total_cc']['children']);
    }

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




    public function getApplicationId(): int
    {
        return $this->tree['id'];
    }

    public function getTypeOfObjectId(): int
    {
        return $this->tree['id_type_of_object'];
    }

    public function getTotalCCId(): int
    {
        return $this->tree['children']['total_cc']['id'];
    }


}