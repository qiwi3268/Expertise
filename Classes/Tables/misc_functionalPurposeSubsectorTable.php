<?php

// Справочник "Функциональное назначение. Подотрасль"
//
final class misc_functionalPurposeSubsectorTable implements Interface_dependentMiscTableValidate{

    // Имя таблицы корреляции с главным справочником (Функциональное назначение)
    static private string $CORRtableName = 'misc_functional_purpose_subsector_FOR_functional_purpose';

    use Trait_dependentMiscTableValidate;
    // checkExistCORRByIds(int $id_main, int $id_dependent):bool

    // Предназначен для получения ассициативного массива функциональных назначений. Подотраслей,
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : функциональные назначения. Подгруппы
    //
    static public function getAllActive():array {

        $query = "SELECT `id`,
                          `name`
                  FROM `misc_functional_purpose_subsector`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }

    // Предназначен для получения ассоциативного массива подотраслей, упакованных по id-функциональных назначений
    //
    static public function getActive_CORR_FunctionalPurpose(array $functionalPurposes):array {

        return TableUtils::getActiveDependent_CORR_mainAssoc($functionalPurposes,
                                                             self::$CORRtableName,
                                              'misc_functional_purpose',
                                          'misc_functional_purpose_subsector');
    }
}
