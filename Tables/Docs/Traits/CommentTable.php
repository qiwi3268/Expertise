<?php


namespace Tables\Docs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;

use Lib\DataBase\ParametrizedQuery;
use Lib\DataBase\SimpleQuery;
use Tables\CommonTraits\deleteById as deleteByIdTrait;
use Tables\Helpers\Helper as TableHelper;


/**
 * Реализует общие методы для таблиц документа "Замечание"
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 * - stageTableName с именем таблицы стадий для данного документа
 *
 */
trait CommentTable
{
    use deleteByIdTrait;


    /**
     * Предназначен для создания записи в таблице документа замечания
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @param string $text текст замечания
     * @param string $normative_document ссылка на нормативный документ
     * @param int $no_files отметка файлов не требуется
     * @param string|null $note личная заметка
     * @param int $id_comment_criticality справочник критичности замечания
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        int $id_author,
        string $text,
        string $normative_document,
        int $no_files,
        ?string $note,
        int $id_comment_criticality
    ): int {

        $table = self::$tableName;

        // id_stage - 1
        // responsible_type - type_4
        $bindParams = [$id_main_document, $id_author, 1, 'type_4', $text, $normative_document, $no_files, $note, $id_comment_criticality];
        $values = TableHelper::getValuesWithoutNullForInsert($bindParams);


        $query = "INSERT INTO `{$table}`
                     (`id_main_document`,
                      `id_author`,
                      `id_stage`,
                      `responsible_type`,
                      `text`,
                      `normative_document`,
                      `no_files`,
                      `note`,
                      `id_comment_criticality`,
                      `date_creation`)
                  VALUES
                     ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }


    /**
     * Предназначен для получения ассоциативных массивов замечаний по id главных документов
     *
     * @param int[] $ids индексный массив с id главных документов (разделов)
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     * @throws TablesEx
     */
    static public function getAllAssocByIdsMainDocument(array $ids): ?array
    {
        $table = self::$tableName;
        $stageTable = self::$stageTableName;

        $condition = TableHelper::getConditionForIN($ids);

        $query = "SELECT `{$table}`.`id`,
                         `{$table}`.`id_main_document`,
                         `user`.`id` as `author_id`,
                         `user`.`last_name`,
	                     `user`.`first_name`,
                         `user`.`middle_name`,
                         `{$stageTable}`.`name` as `stage_name`,
                         `{$stageTable}`.`description` as `stage_description`,
                         `{$table}`.`text`,
                         `{$table}`.`normative_document`,
                         `{$table}`.`no_files`,
                         `{$table}`.`note`,
                         `{$table}`.`id_comment_criticality`,
                         `misc_comment_criticality`.`name` as `name_comment_criticality`
                  FROM `{$table}`
                  JOIN (`user`)
                     ON (`{$table}`.`id_author`=`user`.`id`)
                  JOIN (`{$stageTable}`)
                     ON (`{$table}`.`id_stage`=`{$stageTable}`.`id`)
                  JOIN (`misc_comment_criticality`)
                     ON (`{$table}`.`id_comment_criticality`=`misc_comment_criticality`.`id`)
                  WHERE `{$table}`.`id_main_document` IN ({$condition})";
        $result = ParametrizedQuery::getFetchAssoc($query, $ids);

        if (empty($result)) {
            return null;
        }
        foreach ($result as &$arr) {
            TableHelper::restructureMiscToSubarray($arr, 'id_comment_criticality', 'name_comment_criticality', 'comment_criticality');
        }
        unset($arr);

        return $result;
    }


    /**
     * Предназначен для получения сгруппированной статистики по критичности замечаний по id замечания
     *
     * @param int $id_main_document id главного документа
     * @return array индексный массив, с ассоциативными массивами формата:<br>
     * ['name' => 'Техническая ошибка', 'count' => 1], ...
     * @throws DataBaseEx
     */
    static public function getCommentCriticalityGroupsByIdMainDocument(int $id_main_document): array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `name`
                  FROM `misc_comment_criticality`";
        $miscs = SimpleQuery::getFetchAssoc($query);

        $query = "SELECT `misc_comment_criticality`.`id`,
	                     COUNT(*) AS `count`
                  FROM `{$table}`
                  JOIN `misc_comment_criticality`
                     ON (`{$table}`.`id_comment_criticality`=`misc_comment_criticality`.`id`)
                  WHERE `{$table}`.`id_main_document`=?
                  GROUP BY `id_comment_criticality`";
        $groupResult = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);

        $result = [];

        foreach ($miscs as $misc) {

            if (!is_null($index = getFirstArrayEntryIndex($groupResult, 'id', $misc['id']))) {
                $result[] = [
                    'name'  => $misc['name'],
                    'count' => $groupResult[$index]['count']
                ];
            } else {
                $result[] = [
                    'name'  => $misc['name'],
                    'count' => 0
                ];
            }
        }
        return $result;
    }


    /**
     * Предназначен для обновления записи в таблице документа замечания
     *
     * @param int $id id записи
     * @param string $text текст замечания
     * @param string $normative_document ссылка на нормативный документ
     * @param int $no_files отметка файлов не требуется
     * @param string|null $note личная заметка
     * @param int $id_comment_criticality справочник критичности замечания
     * @throws DataBaseEx
     */
    static public function updateById(
        int $id,
        string $text,
        string $normative_document,
        int $no_files,
        ?string $note,
        int $id_comment_criticality
    ): void {

        $table = self::$tableName;

        $params = [
            'text'                   => $text,
            'normative_document'     => $normative_document,
            'no_files'               => $no_files,
            'note'                   => $note,
            'id_comment_criticality' => $id_comment_criticality
        ];

        list(
            'SETPart'    => $SETPart,
            'bindParams' => $bindParams
            ) = TableHelper::getValuesWithoutNullForUpdate($params);

        $query = "UPDATE `{$table}`
                  SET {$SETPart}
                  WHERE `id`=?";

        ParametrizedQuery::set($query, [...$bindParams, $id]);
    }


    /**
     * Предназначен для получения простого массива id записей замечаний по id раздела и id автора
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @return array|null <b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdsByIdMainDocumentAndIdAuthor(int $id_main_document, int $id_author): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `id`
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `id_author`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_main_document, $id_author]);
        return $result ? $result : null;
    }
}
