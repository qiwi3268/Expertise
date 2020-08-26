<?php


// Справочник "Предмет экспертизы"
//
final class misc_expertiseSubjectTable implements Interface_dependentMiscTableValidate{
    
    // Имя таблицы корреляции с главным справочником (Цель обращения)
    static private string $CORRtableName = 'misc_expertise_subject_FOR_expertise_purpose';

    use Trait_dependentMiscTableValidate;
    // checkExistCORRByIds(int $id_main, int $id_dependent):bool

    // Предназначен для получения ассоциативного массива предметов экспертизы, упакованных по id-целей обращений
    //
    static public function getActive_CORR_ExpertisePurpose(array $expertisePurposes):array {

        return TableUtils::getActiveDependent_CORR_mainAssoc($expertisePurposes,
                                                             self::$CORRtableName,
                                              'misc_expertise_purpose',
                                          'misc_expertise_subject');
    }


    // Предназначен для получения ассициативного массива предмета экспертизы по еего id
    // Принимает параметры------------------------------------
    // int : id предмета экспертизы
    // Возвращает параметры-----------------------------------
    // array : предмет экспертизы
    // null  : предмет не существует
    //
    static public function getAssocById(int $id):?array {

        $query = "SELECT *
                  FROM `misc_expertise_subject`
                  WHERE `id`=?";

        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }
}
