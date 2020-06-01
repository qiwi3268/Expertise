<?php


// Справочник "Предмет экспертизы"
//
final class misc_expertiseSubjectTable{

    // Предназначен для получения ассициативного массива предметов экспертизы,
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : цели экспертизы
    //
    // todo не используется
    static public function getAllActive():array {

        $query = "SELECT `id`,
                         `name`
                  FROM `misc_expertise_subject`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

        return SimpleQuery::getFetchAssoc($query);
    }


    // Предназначен для получения массива предметов экспертизы, упакованных по id-целей обращений
    // т.е. к каждой цели обращения есть массив с предметами экспертизы, которые ей соответствуют
    // возвращает данные по возрастанию столбца sort
    // Принимает параметры
    // expertisePurposes array : цели обращений, с id которых формируется условие IN
    // Возвращает параметры-----------------------------------
    // array : предметы экспертизы
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
                         `misc_expertise_subject`.`id`,
                         `misc_expertise_subject`.`name`
                  FROM (SELECT *
                        FROM `misc_expertise_subject_FOR_expertise_purpose` AS `misc`
                        WHERE `misc`.`id_experise_purpose` IN ($condition)) AS `corr`

                  LEFT JOIN `misc_expertise_purpose`
                         ON (`corr`.`id_experise_purpose`=`misc_expertise_purpose`.`id`)
                  LEFT JOIN `misc_expertise_subject`
                         ON (`corr`.`id_expertise_subject`=`misc_expertise_subject`.`id`)

                  WHERE `misc_expertise_subject`.`is_active`=1
                  ORDER BY `misc_expertise_subject`.`sort` ASC";

        $result = SimpleQuery::getFetchAssoc($query);

        // Укладываем виды работ по целям обращений
        $arr = [];
        foreach($result as ['id_ep' => $id_ep, 'id' => $id, 'name' => $name]){

            $arr[$id_ep][] = ['id' => $id, 'name' => $name];
        }

        return $arr;
    }

    // Предназначен для получения ассициативного массива предмета экспертизы по еего id
    // Возвращает параметры-----------------------------------
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


    // Предназначен для проверки существования записи в таблице коррелиции с указанными id
    // цели обращения и предмета экспертизы
    // Принимает параметры-----------------------------------
    // id_expertise_purpose  int : id цели обращения
    // id_expertise_subject  int : id предмета экспертизы
    // Возвращает параметры----------------------------------
    // true   : корреляция существует существует
    // false  : корреляция не существует
    //
    static public function checkExist_CORR_ExpertisePurposeByIds(int $id_expertise_purpose, int $id_expertise_subject):bool {

        $query = "SELECT count(*)>0
                  FROM `misc_expertise_subject_FOR_expertise_purpose`
                  WHERE `id_experise_purpose`=? AND `id_expertise_subject`=?";

        return ParametrizedQuery::getSimpleArray($query, [$id_expertise_purpose, $id_expertise_subject])[0];
    }


}
