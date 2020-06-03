<?php


// Справочник "Функциональное назначение"
//
final class misc_functionalPurposeTable implements Interface_miscTableValidate{

    static private string $tableName = 'misc_functional_purpose';

    use Trait_miscTableValidate;

    // Предназначен для получения ассициативного массива функциональных назначений,
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : функциональные назначения
    //
    static public function getAllActive():array {

        $query = "SELECT `id`,
                         `name`
                  FROM `misc_functional_purpose`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }


}
