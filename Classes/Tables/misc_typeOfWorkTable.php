<?php

// Справочник "Вид работ"
//
final class misc_typeOfWorkTable{

    // Предназначен для получения массива видов работ, упакованных по id-целей обращений
    // т.е. к каждой цели обращения есть массив с видами работ, которые ей соответствуют
    // возвращает данные по возрастанию столбца sort
    // Принимает параметры
    // expertisePurposes array : цели обращений, с id которых формируется условие IN
    // Возвращает параметры-----------------------------------
    // array : виды работ
    //
    static public function getActive_CORR_ExpertisePurpose(array $expertisePurposes):array {

        // Генерируем состав оператора IN по id целей обращений
        $purposesIds = [];
        foreach($expertisePurposes as ['id' => $id]){
            $purposesIds[] = $id;
        }
        $condition = implode(',', $purposesIds);

        // corr - таблица корреляции
        $query = "SELECT `misc_expertise_purpose`.`id` AS `id_ep`,
                         `misc_type_of_work`.`id`,
                         `misc_type_of_work`.`name`
                  FROM (SELECT *
                        FROM `misc_type_of_work_FOR_expertise_purpose` AS `misc`
                        WHERE `misc`.`id_experise_purpose` IN ($condition)) AS `corr`

                  LEFT JOIN `misc_expertise_purpose`
                         ON (`corr`.`id_experise_purpose`=`misc_expertise_purpose`.`id`)
                  LEFT JOIN `misc_type_of_work`
                         ON (`corr`.`id_type_of_work`=`misc_type_of_work`.`id`)

                  WHERE `misc_type_of_work`.`is_active`=1
                  ORDER BY `misc_type_of_work`.`sort` ASC";

        $result = SimpleQuery::getFetchAssoc($query);

        // Укладываем виды работ по целям обращений
        $arr = [];
        foreach($result as ['id_ep' => $id_ep, 'id' => $id, 'name' => $name]){

            $arr[$id_ep][] = ['id' => $id, 'name' => $name];
        }

        return $arr;
    }
}
