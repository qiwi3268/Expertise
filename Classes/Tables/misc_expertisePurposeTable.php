<?php


// Справочник "Цель обращения"
//
final class misc_expertisePurposeTable implements Interface_singleMiscTableValidate{

    static private string $tableName = 'misc_expertise_purpose';

    use Trait_singleMiscTableValidate;

    // Предназначен для получения ассициативного массива целей обращения,
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : цели экспертизы
    //
    static public function getAllActive():array {

        $query = "SELECT `id`,
                         `name`
                  FROM `misc_expertise_purpose`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }

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
