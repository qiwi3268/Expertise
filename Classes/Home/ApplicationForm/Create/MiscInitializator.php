<?php


// Предназначен для инициализации справочников в форме анкеты
//
class MiscInitializator extends \Classes\Miscs\Initializator\Initializator{
    
    
    // Количество справочных элементов на странице при пагинации
    protected const PAGINATION_SIZE = 8;
    
    // Инициализация имеющихся справочников
    public function __construct(){
        
        // Справочник "Цель обращения"
        $expertisePurposes = misc_expertisePurposeTable::getAllActive();
        $this->setSingleMisc('expertise_purpose', $expertisePurposes);
    
        // Справочник "Предмет экспертизы" -> корреляция с "Цель обращения"
        $this->setDependentMisc('expertise_subjects', misc_expertiseSubjectTable::getActive_CORR_ExpertisePurpose($expertisePurposes));
    
        // Справочник "Вид объекта"
        $this->setSingleMisc('type_of_object', misc_typeOfObjectTable::getAllActive());
    
        // Справочник "Функциональное назначение"
        $functionalPurposes = misc_functionalPurposeTable::getAllActive();
        $this->setSingleMisc('functional_purpose', $functionalPurposes);
    
        // Справочник "Функциональное назначение. Подотрасль" -> корреляция с "Функциональное назначение"
        $this->setDependentMisc('functional_purpose_subsector', misc_functionalPurposeSubsectorTable::getActive_CORR_FunctionalPurpose($functionalPurposes));
    
        // Справочник "Функциональное назначение. Группа" -> корреляция с "Функциональное назначение. Подотрасль"
        $this->setDependentMisc('functional_purpose_group', misc_functionalPurposeGroupTable::getActive_CORR_FunctionalPurposeSubsector(misc_functionalPurposeSubsectorTable::getAllActive()));
    
        // Справочник "Вид работ" -> корреляция с "Цель обращения"
        $this->setDependentMisc('type_of_work', misc_typeOfWorkTable::getActive_CORR_ExpertisePurpose($expertisePurposes));
    
        // Справочник "Тип объекта культурного наследия"
        $this->setSingleMisc('cultural_object_type', misc_culturalObjectTypeTable::getAllActive());
    
        // Справочник "Национальный проект"
        $nationalProjects = misc_nationalProjectTable::getAllActive();
        $this->setSingleMisc('national_project', $nationalProjects);
    
        // Справочник "Федеральный проект" -> корреляция с "Национальный проект"
        $this->setDependentMisc('federal_project', misc_federalProjectTable::getActive_CORR_NationalProject($nationalProjects));
    
        // Справочник "Куратор"
        $this->setSingleMisc('curator', misc_curatorTable::getAllActive());
    
        // Справочник "Уровень бюджета"
        $this->setSingleMisc('budget_level', misc_budgetLevelTable::getAllActive());
        
    }
    
}