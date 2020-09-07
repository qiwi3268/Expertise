<?php


namespace Tables;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;
use Tables\Exceptions\Exception as SelfEx;


final class applications
{

    // Предназначен для создания временной записи заявления
    // - стадия: "Оформление заявления"
    // - ответственные: группы заявителей к заявлению
    // Принимает параметры-----------------------------------
    // id_author         int : id текущего пользователя
    // numerical_name string : числовое имя заявления
    // Возвращает параметры----------------------------------
    // id int : id созданной записи
    //
    static public function createTemporary(int $id_author, string $numerical_name): int
    {
        $query = "INSERT INTO `applications`
                    (`id`, `is_saved`, `id_author`, `id_stage`, `resp_type`, `numerical_name`, `date_creation`)
                  VALUES
                    (NULL, 0, ?, 1, 'type_3', ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_author, $numerical_name]);
    }


    // Предназначен для получения простого массива id заявлений, где пользователь является автором
    // Принимает параметры-----------------------------------
    // id_author         int : id текущего пользователя
    // Возвращает параметры----------------------------------
    // array : в случае, если заявления существуют
    // null  : в противном случае
    //
    static public function getIdsWhereAuthorById(int $id_author): ?array
    {
        $query = "SELECT `id`
                  FROM `applications`
                  WHERE `id_author`=?
                  ORDER BY `id` DESC";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_author]);
        return $result ? $result : null;
    }


    // Предназначен для получения плоского ассоциативного массива заявления по его id
    // * плоский - не содержащий подмассивов. Результирующий массив содержит данные только из таблицы с заявлениями
    // Принимает параметры-----------------------------------
    // id int : id заявления
    // Возвращает параметры----------------------------------
    // array : в случае, если заявление существует
    // null  : в противном случае
    //
    static public function getFlatAssocById(int $id): ?array
    {
        $query = "SELECT *
				  FROM `applications`
                  WHERE `applications`.`id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }


    // Предназначен для получения ассоциативного массива заявления по его id для редактирования заявления
    // Возвращает параметры----------------------------------
    // array : в случае, если заявление существует
    // null  : в противном случае
    //
    static public function getAssocById(int $id): ?array
    {
        $query = "SELECT `applications`.`id` as `id_application`,
                         `applications`.`numerical_name`,
       
                         `applications`.`id_expertise_purpose`,
                         `misc_expertise_purpose`.`name` AS `name_expertise_purpose`,
       
                         `applications`.`additional_information`,
                         `applications`.`object_name`,
       
                         `applications`.`id_type_of_object`,
                         `misc_type_of_object`.`name` AS `name_type_of_object`,
       
                         `applications`.`id_functional_purpose`,
                         `misc_functional_purpose`.`name` AS `name_functional_purpose`,
       
                         `applications`.`id_functional_purpose_subsector`,
                         `misc_functional_purpose_subsector`.`name` AS `name_functional_purpose_subsector`,
       
                         `applications`.`id_functional_purpose_group`,
                         `misc_functional_purpose_group`.`name` AS `name_functional_purpose_group`,
       
                         `applications`.`number_planning_documentation_approval`,
                         `applications`.`date_planning_documentation_approval`,
                         `applications`.`number_GPZU`,
                         `applications`.`date_GPZU`,
                        
                         `applications`.`id_type_of_work`,
                         `misc_type_of_work`.`name` AS `name_type_of_work`,
       
                         `applications`.`cadastral_number`,
       
                         `applications`.`id_cultural_object_type`,
                         `misc_cultural_object_type`.`name` AS `name_cultural_object_type`,
       
                         `applications`.`id_national_project`,
                         `misc_national_project`.`name` AS `name_national_project`,
       
                         `applications`.`id_federal_project`,
                         `misc_federal_project`.`name` AS `name_federal_project`,
       
                         `applications`.`date_finish_building`,
       
                         `applications`.`id_curator`,
                         `misc_curator`.`name` AS `name_curator`
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
                  LEFT JOIN (`misc_cultural_object_type`)
                        ON (`applications`.`id_cultural_object_type`=`misc_cultural_object_type`.`id`)
                  LEFT JOIN (`misc_national_project`)
                        ON (`applications`.`id_national_project`=`misc_national_project`.`id`)
                  LEFT JOIN (`misc_federal_project`)
                        ON (`applications`.`id_federal_project`=`misc_federal_project`.`id`)
                  LEFT JOIN (`misc_curator`)
                        ON (`applications`.`id_curator`=`misc_curator`.`id`)
                  ";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);

        if (empty($result)) {
            return null;
        }
        $result = $result[0];

        // Перекладываем каждый справочник в отдельный подмассив
        self::restructureMiscToSubarray($result, 'id_expertise_purpose', 'name_expertise_purpose', 'expertise_purpose');
        self::restructureMiscToSubarray($result, 'id_type_of_object', 'name_type_of_object', 'type_of_object');
        self::restructureMiscToSubarray($result, 'id_functional_purpose', 'name_functional_purpose', 'functional_purpose');
        self::restructureMiscToSubarray($result, 'id_functional_purpose_subsector', 'name_functional_purpose_subsector', 'functional_purpose_subsector');
        self::restructureMiscToSubarray($result, 'id_functional_purpose_group', 'name_functional_purpose_group', 'functional_purpose_group');
        self::restructureMiscToSubarray($result, 'id_type_of_work', 'name_type_of_work', 'type_of_work');
        self::restructureMiscToSubarray($result, 'id_cultural_object_type', 'name_cultural_object_type', 'cultural_object_type');
        self::restructureMiscToSubarray($result, 'id_national_project', 'name_national_project', 'national_project');
        self::restructureMiscToSubarray($result, 'id_federal_project', 'name_federal_project', 'federal_project');
        self::restructureMiscToSubarray($result, 'id_curator', 'name_curator', 'curator');

        // Предметы экспертизы
        $queryExpertiseSubjects = "SELECT `misc_expertise_subject`.`id`,
                                          `misc_expertise_subject`.`name`
                                   FROM (SELECT * FROM `expertise_subject`
                                         WHERE `id_application`=?) AS `expertise_subject`
                                   LEFT JOIN `misc_expertise_subject`
                                         ON (`expertise_subject`.`id_expertise_subject`=`misc_expertise_subject`.`id`)
                                   ORDER BY `misc_expertise_subject`.`sort` ASC";

        $expertiseSubjects = ParametrizedQuery::getFetchAssoc($queryExpertiseSubjects, [$id]);

        if (empty($expertiseSubjects)) {

            $expertiseSubjects = null;
        }

        $result['expertise_subjects'] = $expertiseSubjects;

        return $result;
    }


    // Предназначен для реструктуризации ассоциативного массива заявления
    // Перекладывает полученные данные о справочнике в отдельный подмассив. В случае, если данные null, то
    // новое свойство также null
    // Полученные данные id_misc и name_misc вырезаются из массива
    // Принимает параметры-----------------------------------
    // &result           array : ссылка на результирующий запрос в БД
    // id_misc          string : id справочника из запроса в БД
    // name_misc        string : имя справочника из запроса в БД
    // restructuredName string : имя нового свойства, в которое будет записаны 'id' и 'name'
    // Выбрасывает исключения--------------------------------
    // Tables\Exceptions\Exception :
    //    в массиве result отсутствует(ют) свойства id_misc и/или name_misc
    //
    static private function restructureMiscToSubarray(
        array &$result,
        string $id_misc,
        string $name_misc,
        string $restructuredName
    ): void {

        if (!array_key_exists($id_misc, $result) || !array_key_exists($name_misc, $result)) {
            throw new SelfEx("В массиве result отсутствует(ют) свойства: '{$id_misc}' и/или '{$name_misc}'");
        }

        if (is_null($result[$id_misc])) {

            $result[$restructuredName] = null;
        } else {

            $result[$restructuredName]['id'] = $result[$id_misc];
            $result[$restructuredName]['name'] = $result[$name_misc];
        }
        unset($result[$id_misc], $result[$name_misc]);
    }


    // Предназначен для проверки существования заявления по его id
    // Принимает параметры-----------------------------------
    // id  int : id заявления
    // Возвращает параметры----------------------------------
    // true   : заявление существует
    // false  : заявление не существует
    //
    static public function checkExistById(int $id): bool
    {
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
    static public function smartUpdateById(array $data, int $id): void
    {
        // Предварительный массив для склейки запроса
        $queryPartsArr = [];
        // Параметры для bind'а
        $bindParams = [];

        foreach ($data as $columnName => $value) {

            if (is_null($value)) {

                // NULL значения не добавляем к bind-параметрам
                $queryPartsArr[] = "`$columnName`=NULL";
            } else {

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
    static public function getAllUnsaved(): array
    {
        $query = "SELECT *
                  FROM `applications`
                  WHERE `is_saved`=0";

        return SimpleQuery::getFetchAssoc($query);
    }


    // todo
    // тестовый метод для работы крона
    static public function deleteFromIdsArray(array $ids): void
    {
        $condition = implode(',', $ids);

        $query = "DELETE
                  FROM `applications`
                  WHERE `id` IN ($condition)";

        SimpleQuery::set($query);
    }

}