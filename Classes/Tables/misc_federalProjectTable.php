<?php

// Справочник "Федеральный проект"
//
final class misc_federalProjectTable implements Interface_dependentMiscTableValidate{

    // Имя таблицы корреляции с главным справочником (Национальный проект)
    static private string $CORRtableName = 'misc_federal_project_FOR_national_project';

    use Trait_dependentMiscTableValidate;
    // checkExistCORRByIds(int $id_main, int $id_dependent):bool

    // Предназначен для получения ассоциативного массива федеральных проектов, упакованных по id-национальных проектов
    //
    static public function getActive_CORR_NationalProject(array $nationalProjects):array {

        return TableUtils::getActiveDependent_CORR_mainAssoc($nationalProjects,
                                                             self::$CORRtableName,
                                              'misc_national_project',
                                          'misc_federal_project');
    }
}
