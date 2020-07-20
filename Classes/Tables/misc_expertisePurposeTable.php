<?php


// Справочник "Цель обращения"
//
final class misc_expertisePurposeTable implements Interface_singleMiscTableValidate{

    static private string $tableName = 'misc_expertise_purpose';

    use Trait_singleMiscTableValidate;
    // checkExistById(int $id):bool

    use Trait_singleMiscTable;
    // getAllActive():array

    // Предназначен для получения ассициативного массива цели обращения по её id
    // Возвращает параметры-----------------------------------
    // int : id цели обращения
    // Возвращает параметры-----------------------------------
    // array : цель обращения
    // null  : цель не существует
    //
    static public function getAssocById(int $id):?array {

        $query = "SELECT *
                  FROM `misc_expertise_purpose`
                  WHERE `id`=?";

        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }


}
