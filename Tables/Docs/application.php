<?php


namespace Tables\Docs;

use Tables\Exceptions\Tables as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;

use Tables\Docs\Interfaces\Document;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;
use Tables\Helpers\Helper as TableHelper;

use Tables\Docs\Traits\Document as DocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_application'</i>
 *
 */
final class application implements Document, Existent, Responsible
{

    static private string $tableName = 'doc_application';
    static private string $stageTableName = 'stage_application';

    use DocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;


    /**
     * Предназначен для создлания записи в таблице
     *
     * Создается временная запись, т.к. is_saved = 0<br>
     * Стадия: "Оформление заявления"
     *
     * @param int $id_author id автора записи
     * @param string $numerical_name числовое имя заявления
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function createTemporary(int $id_author, string $numerical_name): int
    {
        $query = "INSERT INTO `doc_application`
                    (`id`, `is_saved`, `id_author`, `id_stage`, `responsible_type`, `numerical_name`, `date_creation`)
                  VALUES
                    (NULL, 0, ?, 1, 'type_3', ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_author, $numerical_name]);
    }


    /**
     * Предназначен для получения простого массива id заявлений, где пользователь является автором
     *
     * @param int $id_author id автора записи
     * @return array|null <b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdsWhereAuthorById(int $id_author): ?array
    {
        $query = "SELECT `id`
                  FROM `doc_application`
                  WHERE `id_author`=?
                  ORDER BY `id` DESC";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_author]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для получения "плоского" ассоциативного массива заявления по его id
     *
     * <b>*</b> Плоский - не содержащий подмассивов<br>
     * Результирующий массив содержит данные только из таблицы с заявлениями
     *
     * @param int $id id заявления
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getFlatAssocById(int $id): ?array
    {
        $query = "SELECT *
				  FROM `doc_application`
                  WHERE `doc_application`.`id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }


    /**
     * Предназначен для проверки существования записи по id заявления, у которого
     * id_type_of_object не NULL
     *
     * @param int $id id заявления
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistByIdWhereIdTypeOfObjectNN(int $id): bool
    {
        $query = "SELECT COUNT(*)>0
                  FROM `doc_application`
                  WHERE `id`=? AND `id_type_of_object` IS NOT NULL";

        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }


    /**
     * Предназначен для получения id вида работ по id заявления
     *
     * @param int $id id заявления
     * @return int|null <b>int</b> если вид работ существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdTypeOfObjectById(int $id): ?int
    {
        $query = "SELECT `id_type_of_object`
				  FROM `doc_application`
                  WHERE `doc_application`.`id`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id]);
        return $result ? $result[0] : null;
    }


    /**
     * Предназначен для получения ассоциативного массива заявления по его id
     *
     * @param int $id id заявления
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     * @throws SelfEx
     */
    static public function getAssocById(int $id): ?array
    {
        $query = "SELECT `doc_application`.`id` as `id_application`,
                         `doc_application`.`numerical_name`,
       
                         `doc_application`.`id_expertise_purpose`,
                         `misc_expertise_purpose`.`name` AS `name_expertise_purpose`,
       
                         `doc_application`.`additional_information`,
                         `doc_application`.`object_name`,
       
                         `doc_application`.`id_type_of_object`,
                         `misc_type_of_object`.`name` AS `name_type_of_object`,
       
                         `doc_application`.`id_functional_purpose`,
                         `misc_functional_purpose`.`name` AS `name_functional_purpose`,
       
                         `doc_application`.`id_functional_purpose_subsector`,
                         `misc_functional_purpose_subsector`.`name` AS `name_functional_purpose_subsector`,
       
                         `doc_application`.`id_functional_purpose_group`,
                         `misc_functional_purpose_group`.`name` AS `name_functional_purpose_group`,
       
                         `doc_application`.`number_planning_documentation_approval`,
                         `doc_application`.`date_planning_documentation_approval`,
                         `doc_application`.`number_GPZU`,
                         `doc_application`.`date_GPZU`,
                        
                         `doc_application`.`id_type_of_work`,
                         `misc_type_of_work`.`name` AS `name_type_of_work`,
       
                         `doc_application`.`cadastral_number`,
       
                         `doc_application`.`id_cultural_object_type`,
                         `misc_cultural_object_type`.`name` AS `name_cultural_object_type`,
       
                         `doc_application`.`id_national_project`,
                         `misc_national_project`.`name` AS `name_national_project`,
       
                         `doc_application`.`id_federal_project`,
                         `misc_federal_project`.`name` AS `name_federal_project`,
       
                         `doc_application`.`date_finish_building`,
       
                         `doc_application`.`id_curator`,
                         `misc_curator`.`name` AS `name_curator`
                  FROM (SELECT * FROM `doc_application`
                        WHERE `doc_application`.`id`=?) AS `doc_application`
                  LEFT JOIN (`misc_expertise_purpose`)
                        ON (`doc_application`.`id_expertise_purpose`=`misc_expertise_purpose`.`id`)
                  LEFT JOIN (`misc_type_of_object`)
                        ON (`doc_application`.`id_type_of_object`=`misc_type_of_object`.`id`)
                  LEFT JOIN (`misc_functional_purpose`)
                        ON (`doc_application`.`id_functional_purpose`=`misc_functional_purpose`.`id`)
                  LEFT JOIN (`misc_functional_purpose_subsector`)
                        ON (`doc_application`.`id_functional_purpose_subsector`=`misc_functional_purpose_subsector`.`id`)
                  LEFT JOIN (`misc_functional_purpose_group`)
                        ON (`doc_application`.`id_functional_purpose_group`=`misc_functional_purpose_group`.`id`)
                  LEFT JOIN (`misc_type_of_work`)
                        ON (`doc_application`.`id_type_of_work`=`misc_type_of_work`.`id`)
                  LEFT JOIN (`misc_cultural_object_type`)
                        ON (`doc_application`.`id_cultural_object_type`=`misc_cultural_object_type`.`id`)
                  LEFT JOIN (`misc_national_project`)
                        ON (`doc_application`.`id_national_project`=`misc_national_project`.`id`)
                  LEFT JOIN (`misc_federal_project`)
                        ON (`doc_application`.`id_federal_project`=`misc_federal_project`.`id`)
                  LEFT JOIN (`misc_curator`)
                        ON (`doc_application`.`id_curator`=`misc_curator`.`id`)
                  ";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);

        if (empty($result)) {
            return null;
        }
        $result = $result[0];

        // Перекладываем каждый справочник в отдельный подмассив
        TableHelper::restructureMiscToSubarray($result, 'id_expertise_purpose', 'name_expertise_purpose', 'expertise_purpose');
        TableHelper::restructureMiscToSubarray($result, 'id_type_of_object', 'name_type_of_object', 'type_of_object');
        TableHelper::restructureMiscToSubarray($result, 'id_functional_purpose', 'name_functional_purpose', 'functional_purpose');
        TableHelper::restructureMiscToSubarray($result, 'id_functional_purpose_subsector', 'name_functional_purpose_subsector', 'functional_purpose_subsector');
        TableHelper::restructureMiscToSubarray($result, 'id_functional_purpose_group', 'name_functional_purpose_group', 'functional_purpose_group');
        TableHelper::restructureMiscToSubarray($result, 'id_type_of_work', 'name_type_of_work', 'type_of_work');
        TableHelper::restructureMiscToSubarray($result, 'id_cultural_object_type', 'name_cultural_object_type', 'cultural_object_type');
        TableHelper::restructureMiscToSubarray($result, 'id_national_project', 'name_national_project', 'national_project');
        TableHelper::restructureMiscToSubarray($result, 'id_federal_project', 'name_federal_project', 'federal_project');
        TableHelper::restructureMiscToSubarray($result, 'id_curator', 'name_curator', 'curator');

        // Предметы экспертизы
        $queryExpertiseSubjects = "SELECT `misc_expertise_subject`.`id`,
                                          `misc_expertise_subject`.`name`
                                   FROM (SELECT * FROM `expertise_subject`
                                         WHERE `id_application`=?) AS `expertise_subject`
                                   LEFT JOIN `misc_expertise_subject`
                                         ON (`expertise_subject`.`id_expertise_subject`=`misc_expertise_subject`.`id`)
                                   ORDER BY `misc_expertise_subject`.`sort`";

        $expertiseSubjects = ParametrizedQuery::getFetchAssoc($queryExpertiseSubjects, [$id]);

        if (empty($expertiseSubjects)) {

            $expertiseSubjects = null;
        }

        $result['expertise_subjects'] = $expertiseSubjects;

        return $result;
    }


    /**
     * Предназначен для умного обновления заявления по его id
     *
     * Метод выполняет update только тех столбцов, которые ему переданы
     *
     * @param array $data ассоциативный массив формата:<br>
     * <i>Ключ</i> - название столбца в таблице<br>
     * <i>Значение</i> - новое значение, которое будет установлено
     * @param int $id id заявления
     * @throws DataBaseEx
     */
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

        $query = "UPDATE `doc_application`
                  SET $queryPart
                  WHERE `id`=?";

        ParametrizedQuery::set($query, [...$bindParams, $id]);
    }


    /**
     * Предназначен для получения ассоциативных массивов несохраненных заявлений
     *
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereUnsaved(): ?array
    {
        $query = "SELECT *
                  FROM `doc_application`
                  WHERE `is_saved`=0";

        return SimpleQuery::getFetchAssoc($query);
    }


    /**
     * Предназначен для удаления записей в таблице, имеющих id, как в принятом
     * индексном массиве с id заявлений
     *
     * @param array $ids индексный массив с id заявлений
     * @throws DataBaseEx
     */
    static public function deleteFromIdsArray(array $ids): void
    {
        $condition = implode(',', $ids);

        $query = "DELETE
                  FROM `doc_application`
                  WHERE `id` IN ($condition)";
        SimpleQuery::set($query);
    }
}