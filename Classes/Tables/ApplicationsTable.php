<?php


final class ApplicationsTable{


    // Предназначен для создания временной записи заявления
    // Принимает параметры-----------------------------------
    // id_author         int : id текущего пользователя
    // numerical_name string : числовое имя заявления
    // Возвращает параметры----------------------------------
    // id int : id созданной записи
    //
    static public function createTemporary(int $id_author, string $numerical_name):int {

        $query = "INSERT INTO `applications`
                    (`id`, `is_saved`, `id_author`, `numerical_name`, `date_creation`)
                  VALUES
                    (NULL, 0, ?, ?, UNIX_TIMESTAMP())";

        return ParametrizedQuery::set($query, [$id_author, $numerical_name]);
    }


    // Предназначен для получения простого массива id заявлений, где пользователь является автором
    // Принимает параметры-----------------------------------
    // id_author         int : id текущего пользователя
    // Возвращает параметры----------------------------------
    // array : в случае, если заявления существуют
    // null  : в противном случае
    //
    static public function getIdsWhereAuthorById(int $id_author):?array {

        $query = "SELECT `id`
                  FROM `applications`
                  WHERE `id_author`=?
                  ORDER BY `id` DESC";

        $result = ParametrizedQuery::getSimpleArray($query, [$id_author]);

        return $result ? $result : null;
    }


    // Предназначен для получения плоского ассоциативного массива заявления по его id
    // Принимает параметры-----------------------------------
    // id int : id заявления
    // Возвращает параметры----------------------------------
    // array : в случае, если заявление существует
    // null  : в противном случае
    //
    static public function getFlatAssocById(int $id):?array {

        $query = "SELECT `applications`.`id`,
                         `applications`.`is_saved`,
                         `applications`.`numerical_name`,
                         `applications`.`id_expertise_purpose`,
                         `applications`.`additional_information`,
                         `applications`.`object_name`,
                         `applications`.`id_type_of_object`,
                         `applications`.`id_functional_purpose`,
                         `applications`.`id_functional_purpose_subsector`,
                         `applications`.`id_functional_purpose_group`,
                         `applications`.`number_planning_documentation_approval`,
                         `applications`.`date_planning_documentation_approval`,
                         `applications`.`number_GPZU`,
                         `applications`.`date_GPZU`,
                         `applications`.`id_type_of_work`,
                         `applications`.`cadastral_number`,
                         `applications`.`date_creation`
				  FROM `applications`
                  WHERE `applications`.`id`=?";

        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }


    // Предназначен для получения ассоциативного массива заявления по его id для просмотра заявления
    static public function getAssocByIdForView(int $id):?array {
        $query = "SELECT `applications`.`id`,
                         `applications`.`numerical_name`,
                         `misc_expertise_purpose`.`name` AS `expertise_purpose`,
                         `applications`.`additional_information`,
                         `applications`.`object_name`,
                         `misc_type_of_object`.`name` AS `type_of_object`,
                         `misc_functional_purpose`.`name` AS `functional_purpose`,
                         `misc_functional_purpose_subsector`.`name` AS `functional_purpose_subsector`,
                         `misc_functional_purpose_group`.`name` AS `functional_purpose_group`,
                         `applications`.`number_planning_documentation_approval`,
                         `applications`.`date_planning_documentation_approval`,
                         `applications`.`number_GPZU`,
                         `applications`.`date_GPZU`,
                         `misc_type_of_work`.`name` AS `type_of_work`,
                         `applications`.`cadastral_number`
                  FROM (SELECT * FROM `applications`
                        WHERE `applications`.`id`=?) AS `applications`
                  LEFT JOIN (`misc_expertise_purpose`)
                        ON (`applications`.`id_expertise_purpose`=`misc_expertise_purpose`.`id`)
                  LEFT JOIN (`misc_type_of_object`)
                        ON (`applications`.`id_type_of_object`=`misc_type_of_object`.`id`)
                  LEFT JOIN (`misc_functional_purpose`)
                        ON (`applications`.`id_functional_purpose`=`misc_functional_purpose`.`id`)
                  LEFT JOIN (`misc_functional_purpose_subsector`)
                        ON (`applications`.`id_functional_purpose_subsector`=`misc_functional_purpose_subsector`.`id`)
                  LEFT JOIN (`misc_functional_purpose_group`)
                        ON (`applications`.`id_functional_purpose_group`=`misc_functional_purpose_group`.`id`)
                  LEFT JOIN (`misc_type_of_work`)
                        ON (`applications`.`id_type_of_work`=`misc_type_of_work`.`id`)
                  ";

        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);

        if(empty($result)){
            return null;
        }
        $result = $result[0];

        // Предметы экспертизы
        $queryExpertiseSubjects = "SELECT `misc_expertise_subject`.`name`
                                   FROM (SELECT * FROM `expertise_subject`
                                         WHERE `id_application`=?) AS `expertise_subject`
                                   LEFT JOIN `misc_expertise_subject`
                                         ON (`expertise_subject`.`id_expertise_subject`=`misc_expertise_subject`.`id`)
                                   ORDER BY `misc_expertise_subject`.`sort` ASC";

        $expertiseSubjects = ParametrizedQuery::getFetchAssoc($queryExpertiseSubjects, [$id]);

        if(empty($expertiseSubjects)){

            $expertiseSubjects = null;
        }else{

            // Переносим каждую цель из подмассива на один уровень вверх
            foreach($expertiseSubjects AS &$subject){
                $subject = $subject['name'];
            }
            unset($subject);
        }

        $result['expertise_subjects'] = $expertiseSubjects;


        return $result;
    }
    
    
    
    // Предназначен для получения ассоциативного массива заявления по его id для редактирования заявления
    static public function getAssocByIdForEdit(int $id):?array {
        $query = "SELECT `applications`.`id`,
                         `applications`.`numerical_name`,
                         `applications`.`id_expertise_purpose`,
                         `misc_expertise_purpose`.`name` AS `expertise_purpose`,
                         `applications`.`additional_information`,
                         `applications`.`object_name`,
                         `applications`.`id_type_of_object`,
                         `misc_type_of_object`.`name` AS `type_of_object`,
                         `applications`.`id_functional_purpose`,
                         `misc_functional_purpose`.`name` AS `functional_purpose`,
                         `applications`.`id_functional_purpose_subsector`,
                         `misc_functional_purpose_subsector`.`name` AS `functional_purpose_subsector`,
                         `applications`.`id_functional_purpose_group`,
                         `misc_functional_purpose_group`.`name` AS `functional_purpose_group`,
                         `applications`.`number_planning_documentation_approval`,
                         `applications`.`date_planning_documentation_approval`,
                         `applications`.`number_GPZU`,
                         `applications`.`date_GPZU`,
                         `misc_type_of_work`.`name` AS `type_of_work`,
                         `applications`.`cadastral_number`
                  FROM (SELECT * FROM `applications`
                        WHERE `applications`.`id`=?) AS `applications`
                  LEFT JOIN (`misc_expertise_purpose`)
                        ON (`applications`.`id_expertise_purpose`=`misc_expertise_purpose`.`id`)
                  LEFT JOIN (`misc_type_of_object`)
                        ON (`applications`.`id_type_of_object`=`misc_type_of_object`.`id`)
                  LEFT JOIN (`misc_functional_purpose`)
                        ON (`applications`.`id_functional_purpose`=`misc_functional_purpose`.`id`)
                  LEFT JOIN (`misc_functional_purpose_subsector`)
                        ON (`applications`.`id_functional_purpose_subsector`=`misc_functional_purpose_subsector`.`id`)
                  LEFT JOIN (`misc_functional_purpose_group`)
                        ON (`applications`.`id_functional_purpose_group`=`misc_functional_purpose_group`.`id`)
                  LEFT JOIN (`misc_type_of_work`)
                        ON (`applications`.`id_type_of_work`=`misc_type_of_work`.`id`)
                  ";
        
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        
        if(empty($result)){
            return null;
        }
        $result = $result[0];
        
        // Предметы экспертизы
        $queryExpertiseSubjects = "SELECT `misc_expertise_subject`.`id`,
                                          `misc_expertise_subject`.`name`
                                   FROM (SELECT * FROM `expertise_subject`
                                         WHERE `id_application`=?) AS `expertise_subject`
                                   LEFT JOIN `misc_expertise_subject`
                                         ON (`expertise_subject`.`id_expertise_subject`=`misc_expertise_subject`.`id`)
                                   ORDER BY `misc_expertise_subject`.`sort` ASC";
        
        $expertiseSubjects = ParametrizedQuery::getFetchAssoc($queryExpertiseSubjects, [$id]);
        
        if(empty($expertiseSubjects)){
            
            $expertiseSubjects = null;
        }
        
        $result['expertise_subjects'] = $expertiseSubjects;
        
        return $result;
    }
    
    
    


    // Предназначен для проверки существования заявления по его id
    // Принимает параметры-----------------------------------
    // id  int : id заявления
    // Возвращает параметры----------------------------------
    // true   : заявление существует
    // false  : заявление не существует
    //
    static public function checkExistById(int $id):bool {

        $query = "SELECT count(*)>0
                  FROM `applications`
                  WHERE `id`=?";

        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }


    // Предназначен для умного обновления заявления по его id
    // Метод выполняет update только тех столбцов, которые ему переданы
    // Принимает параметры-----------------------------------
    // data array : ассоциативный массив формата:
    //              ключ     - название столбца в таблице
    //              значение - новое значение, которое будет установлено
    // id     int : id заявления
    // Возвращает параметры----------------------------------
    //
    static public function smartUpdateById(array $data, int $id):void {

        // Предварительный массив для склейки запроса
        $queryPartsArr = [];
        // Параметры для bind'а
        $bindParams = [];

        foreach($data as $columnName => $value){

            if(is_null($value)){

                // NULL значения не добавляем к bind-параметрам
                $queryPartsArr[] = "`$columnName`=NULL";
            }else{

                // Остальные значения добавляем как параметризованные
                $queryPartsArr[] = "`$columnName`=?";
                $bindParams[] = $value;
            }
        }

        // Соединение частей запроса в одну строку с разделителем
        $queryPart = implode(', ', $queryPartsArr);

        $query = "UPDATE `applications`
                  SET $queryPart
                  WHERE `id`=?";

        ParametrizedQuery::set($query, [...$bindParams, $id]);
    }







    // todo
    // тестовый метод для работы крона
    // Предназначен для получения всех несохраненных заявлений
    // Возвращает параметры-----------------------------------
    // array : несохраненные заявления
    //
    static public function getAllUnsaved():array {

        $query = "SELECT *
                  FROM `applications`
                  WHERE `is_saved`=0";

        return SimpleQuery::getFetchAssoc($query);
    }


    // todo
    // тестовый метод для работы крона
    static public function deleteFromIdsArray(array $ids){

        $condition = implode(',', $ids);

        $query = "DELETE
                  FROM `applications`
                  WHERE `id` IN ($condition)";

        SimpleQuery::set($query);
    }

}