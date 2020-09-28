<?php


namespace Tables;

use Tables\Exceptions\Tables as SelfEx;


/**
 * Предназначен для получения названий классов таблиц в зависимости от вида объекта
 *
 */
class DocumentationTypeTableLocator
{

    /**
     * id вида объекта
     *
     */
    private int $typeOfObjectId;


    /**
     * Конструктор класса
     *
     * @param int $typeOfObjectId id вида объекта
     * @throws SelfEx
     */
    public function __construct(int $typeOfObjectId)
    {
        if (
            $typeOfObjectId != 1    // Производственные / непроизводственные
            && $typeOfObjectId != 2 // Линейные
        ) {
            throw new SelfEx("Получен неопределенный вид объекта: '{$typeOfObjectId}'", 5);
        }
        $this->typeOfObjectId = $typeOfObjectId;
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Actions\section_documentation_1}<br>
     * или<br>
     * {@see \Tables\Actions\section_documentation_2}
     */
    public function getActionsSection(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Actions\section_documentation_1';
        } else {
            return '\Tables\Actions\section_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Docs\section_documentation_1}<br>
     * или<br>
     * {@see \Tables\Docs\section_documentation_2}
     */
    public function getDocsSection(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Docs\section_documentation_1';
        } else {
            //todo на вырост
            return '\Tables\Docs\section_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Docs\Relations\section_documentation_1}<br>
     * или<br>
     * {@see \Tables\Docs\Relations\section_documentation_2}
     */
    public function getDocsRelationsSection(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Docs\Relations\section_documentation_1';
        } else {
            //todo на вырост
            return '\Tables\Docs\Relations\section_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\order_341\assigned_expert_documentation_1}<br>
     * или<br>
     * {@see \Tables\order_341\assigned_expert_documentation_2}
     */
    public function getOrder341AssignedExpert(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\order_341\assigned_expert_documentation_1';
        } else {
            //todo на вырост
            return '\Tables\order_341\assigned_expert_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\order_341\main_block_documentation_1}<br>
     * или<br>
     * {@see \Tables\order_341\main_block_documentation_2}
     */
    public function getOrder341MainBlock(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\order_341\main_block_documentation_1';
        } else {
            //todo на вырост
            return '\Tables\order_341\main_block_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Responsible\type_4\section_documentation_1}<br>
     * или<br>
     * {@see \Tables\Responsible\type_4\section_documentation_2}
     */
    public function getResponsibleType4Section(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Responsible\type_4\section_documentation_1';
        } else {
            //todo на вырост
            return '\Tables\Responsible\type_4\section_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Structures\documentation_1}<br>
     * или<br>
     * {@see \Tables\Structures\documentation_2}
     */
    public function getStructures(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Structures\documentation_1';
        } else {
            //todo на вырост
            return '\Tables\Structures\documentation_2';
        }
    }
}