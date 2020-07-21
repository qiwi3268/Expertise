<?php

// Справочник "Функциональное назначение. Группа"
//
final class misc_functionalPurposeGroupTable implements Interface_dependentMiscTableValidate{
    
    // Имя таблицы корреляции с главным справочником (Функциональное назначение. Подотрасль)
    static private string $CORRtableName = 'misc_functional_purpose_group_FOR_functional_purpose_subsector';

    use Trait_dependentMiscTableValidate;
    // checkExistCORRByIds(int $id_main, int $id_dependent):bool
    

    // Предназначен для получения ассоциативного массива групп, упакованных по id-подотраслей
    //
    static public function getActive_CORR_FunctionalPurposeSubsector(array $functionalPurposeSubsectors):array {

        return TableUtils::getActiveDependent_CORR_mainAssoc($functionalPurposeSubsectors,
                                                             self::$CORRtableName,
                                              'misc_functional_purpose_subsector',
                                          'misc_functional_purpose_group');
    }
}
