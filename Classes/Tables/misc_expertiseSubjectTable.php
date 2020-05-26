<?php


// Справочник "Предмет экспертизы"
//
final class misc_expertiseSubjectTable{

    // Предназначен для получения ассициативного массива предметов экспертизы,
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : цели экспертизы
    //
    static public function getAllActive():array {

        $query = "SELECT `id`,
                         `name`
                  FROM `misc_expertise_subject`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }


    static public function getActive_COOR_ExpertisePurpose(array $expertiseSubjects):array {

    }

}
