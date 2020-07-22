<?php

// Справочник "Функциональное назначение. Подотрасль"
//
final class misc_functionalPurposeSubsectorTable implements Interface_dependentMiscTableValidate{
    
    static private string $tableName = 'misc_functional_purpose_subsector';
    
    // Имя таблицы корреляции с главным справочником (Функциональное назначение)
    static private string $CORRtableName = 'misc_functional_purpose_subsector_FOR_functional_purpose';

    use Trait_dependentMiscTableValidate;
    // checkExistCORRByIds(int $id_main, int $id_dependent):bool
    
    use Trait_singleMiscTable;
    // getAllActive():array
    

    // Предназначен для получения ассоциативного массива подотраслей, упакованных по id-функциональных назначений
    //
    static public function getActive_CORR_FunctionalPurpose(array $functionalPurposes):array {

        return TableUtils::getActiveDependent_CORR_mainAssoc($functionalPurposes,
                                                             self::$CORRtableName,
                                              'misc_functional_purpose',
                                          'misc_functional_purpose_subsector');
    }
}
