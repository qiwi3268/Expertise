<?php


namespace Tables\Locators;

use Tables\Exceptions\Tables as SelfEx;


/**
 * Предназначен для получения названий классов таблиц в зависимости от вида объекта
 *
 */
class TypeOfObjectTableLocator
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
            throw new SelfEx("Получен неопределенный вид объекта: '{$typeOfObjectId}'", 4001);
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
            return '\Tables\Docs\section_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Docs\comment_documentation_1}<br>
     * или<br>
     * {@see \Tables\Docs\comment_documentation_2}
     */
    public function getDocsComment(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Docs\comment_documentation_1';
        } else {
            return '\Tables\Docs\comment_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Docs\group_documentation_1}<br>
     * или<br>
     * {@see \Tables\Docs\group_documentation_2}
     */
    public function getDocsGroup(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Docs\group_documentation_1';
        } else {
            return '\Tables\Docs\group_documentation_2';
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
     * {@see \Tables\AssignedExperts\section_documentation_1}<br>
     * или<br>
     * {@see \Tables\AssignedExperts\section_documentation_2}
     */
    public function getAssignedExpertsSection(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\AssignedExperts\section_documentation_1';
        } else {
            return '\Tables\AssignedExperts\section_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Files\documentation_1}<br>
     * или<br>
     * {@see \Tables\Files\documentation_2}
     */
    public function getFilesDocumentation(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Files\documentation_1';
        } else {
            return '\Tables\Files\documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Order341\main_block_documentation_1}<br>
     * или<br>
     * {@see \Tables\Order341\main_block_documentation_2}
     */
    public function getOrder341MainBlock(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Order341\main_block_documentation_1';
        } else {
            return '\Tables\Order341\main_block_documentation_2';
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
            return '\Tables\Responsible\type_4\section_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Responsible\type_4\comment_documentation_1}<br>
     * или<br>
     * {@see \Tables\Responsible\type_4\comment_documentation_2}
     */
    public function getResponsibleType4Comment(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Responsible\type_4\comment_documentation_1';
        } else {
            return '\Tables\Responsible\type_4\comment_documentation_2';
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
            return '\Tables\Structures\documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Messages\applicant_documentation_1}<br>
     * или<br>
     * {@see \Tables\Messages\applicant_documentation_2}
     */
    public function getMessagesApplicant(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Messages\applicant_documentation_1';
        } else {
            return '\Tables\Messages\applicant_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\Messages\expert_documentation_1}<br>
     * или<br>
     * {@see \Tables\Messages\expert_documentation_2}
     */
    public function getMessagesExpert(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\Messages\expert_documentation_1';
        } else {
            return '\Tables\Messages\expert_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\CommentsInGroup\documentation_1}<br>
     * или<br>
     * {@see \Tables\CommentsInGroup\documentation_2}
     */
    public function getCommentsInGroup(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\CommentsInGroup\documentation_1';
        } else {
            return '\Tables\CommentsInGroup\documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\DescriptivePart\description_documentation_1}<br>
     * или<br>
     * {@see \Tables\DescriptivePart\description_documentation_2}
     */
    public function getDescriptivePartDescription(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\DescriptivePart\description_documentation_1';
        } else {
            return '\Tables\DescriptivePart\description_documentation_2';
        }
    }


    /**
     * Предназначен для получения названия класса таблицы
     * в зависимости от вида объекта
     *
     * @return string
     * {@see \Tables\DescriptivePart\TEP_documentation_1}<br>
     * или<br>
     * {@see \Tables\DescriptivePart\TEP_documentation_2}
     */
    public function getDescriptivePartTEP(): string
    {
        if ($this->typeOfObjectId == 1) {
            return '\Tables\DescriptivePart\TEP_documentation_1';
        } else {
            return '\Tables\DescriptivePart\TEP_documentation_2';
        }
    }
}