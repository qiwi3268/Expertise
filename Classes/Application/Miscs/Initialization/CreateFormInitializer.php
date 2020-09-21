<?php


namespace Classes\Application\Miscs\Initialization;
use Lib\Miscs\Initialization\Initializer;

use Tables\Miscs\{
    budget_level,
    cultural_object_type,
    curator,
    expertise_purpose,
    expertise_subject,
    federal_project,
    functional_purpose,
    functional_purpose_group,
    functional_purpose_subsector,
    national_project,
    type_of_object,
    type_of_work
};


/**
 * Предназначен для инициализации справочников в форме анкеты документа <i>Заявление</i>
 *
 */
class CreateFormInitializer extends Initializer
{

    protected const PAGINATION_SIZE = 8;


    /**
     * Конструктор класса
     *
     * Предназначен для инициализации имеющихся в форме справочников
     *
     */
    public function __construct()
    {
        // Справочник "Цель обращения"
        $this->setSingleMisc('expertise_purpose', expertise_purpose::getAllAssocWhereActive());

        // Справочник "Предмет экспертизы" -> корреляция с "Цель обращения"
        $this->setDependentMisc('expertise_subjects', expertise_subject::getAllAssocWhereActiveCorrMain());

        // Справочник "Вид объекта"
        $this->setSingleMisc('type_of_object', type_of_object::getAllAssocWhereActive());

        // Справочник "Функциональное назначение"
        $this->setSingleMisc('functional_purpose', functional_purpose::getAllAssocWhereActive());

        // Справочник "Функциональное назначение. Подотрасль" -> корреляция с "Функциональное назначение"
        $this->setDependentMisc('functional_purpose_subsector', functional_purpose_subsector::getAllAssocWhereActiveCorrMain());

        // Справочник "Функциональное назначение. Группа" -> корреляция с "Функциональное назначение. Подотрасль"
        $this->setDependentMisc('functional_purpose_group', functional_purpose_group::getAllAssocWhereActiveCorrMain());

        // Справочник "Вид работ" -> корреляция с "Цель обращения"
        $this->setDependentMisc('type_of_work', type_of_work::getAllAssocWhereActiveCorrMain());

        // Справочник "Тип объекта культурного наследия"
        $this->setSingleMisc('cultural_object_type', cultural_object_type::getAllAssocWhereActive());

        // Справочник "Национальный проект"
        $this->setSingleMisc('national_project', national_project::getAllAssocWhereActive());

        // Справочник "Федеральный проект" -> корреляция с "Национальный проект"
        $this->setDependentMisc('federal_project', federal_project::getAllAssocWhereActiveCorrMain());

        // Справочник "Куратор"
        $this->setSingleMisc('curator', curator::getAllAssocWhereActive());

        // Справочник "Уровень бюджета"
        $this->setSingleMisc('budget_level', budget_level::getAllAssocWhereActive());
    }
}