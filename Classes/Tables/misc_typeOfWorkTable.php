<?php

// Справочник "Вид работ"
//
final class misc_typeOfWorkTable implements Interface_dependentMiscTableValidate{

    // Имя таблицы корреляции с главным справочником (Цель обращения)
    static private string $CORRtableName = 'misc_type_of_work_FOR_expertise_purpose';

    use Trait_dependentMiscTableValidate;
    // checkExistCORRByIds(int $id_main, int $id_dependent):bool

    // Предназначен для получения ассоциативного массива видов работ, упакованных по id-целей обращений
    //
    static public function getActive_CORR_ExpertisePurpose(array $expertisePurposes):array {

        return TableUtils::getActiveDependent_CORR_mainAssoc($expertisePurposes,
                                                             self::$CORRtableName,
                                              'misc_expertise_purpose',
                                          'misc_type_of_work');
    }
}
