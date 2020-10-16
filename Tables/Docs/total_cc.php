<?php


namespace Tables\Docs;

use Lib\Exceptions\DataBase as DataBaseEx;

use Tables\Docs\Interfaces\Document;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\Document as DocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;

use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'doc_total_cc'</i>
 *
 */
final class total_cc implements Document, Existent, Responsible
{

    static private string $tableName = 'doc_total_cc';
    static private string $stageTableName = 'stage_total_cc';

    use DocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;


    /**
     * Предназначен для получения ассоциативного массива с названиями
     * дочерних таблиц в зависимости от вида объекта
     *
     * @param int $typeOfObjectId id вида объекта
     * @return array
     */
    static private function getChildTables(int $typeOfObjectId): array
    {
        $tables = [
            1 => [
                'doc_section' => 'doc_section_documentation_1',
                'doc_comment' => 'doc_comment_documentation_1'
            ],
            2 => [
                'doc_section' => 'doc_section_documentation_2',
                'doc_comment' => 'doc_comment_documentation_2'
            ]
        ];
        return $tables[$typeOfObjectId];
    }


    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора записи
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_author): int
    {
        $query = "INSERT INTO `doc_total_cc`
                     (`id_main_document`, `id_author`, `id_stage`,`responsible_type`, `date_creation`)
                  VALUES
                     (?, ?, 1, 'type_4', UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_main_document, $id_author]);
    }


    /**
     * Предназначен для получения id стадии по id сводного замечания / заключения
     *
     * @param int $id id сводного замечания / заключения
     * @return int id стадии
     * @throws DataBaseEx
     */
    static public function getIdStageById(int $id): int
    {
        $query = "SELECT `id_stage`
				  FROM `doc_total_cc`
                  WHERE `doc_total_cc`.`id`=?";
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }


    /**
     * Предназначен для получения индексного массива id дочерних разделов
     *
     * @param int $id id записи
     * @param int $typeOfObjectId id вида объекта для получения дочерней таблицы
     * @return array|null <b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdSectionsById(int $id, int $typeOfObjectId): ?array
    {
        $sectionTable = self::getChildTables($typeOfObjectId)['doc_section'];

        $query = "SELECT `{$sectionTable}`.`id`
                  FROM `{$sectionTable}`
                  WHERE `{$sectionTable}`.`id_main_document`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для получения индексного массива id дочерних замечаний
     *
     * @param int $id id записи
     * @param int $typeOfObjectId id вида объекта для получения дочерних таблиц
     * @return array|null <b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdCommentsById(int $id, int $typeOfObjectId): ?array
    {
        list(
            'doc_section' => $sectionTable,
            'doc_comment' => $commentTable
        ) = self::getChildTables($typeOfObjectId);

        $query = "SELECT `{$commentTable}`.`id`
                  FROM `{$commentTable}`
                  WHERE `{$commentTable}`.`id_main_document` IN
                  (
                     SELECT `{$sectionTable}`.`id`
                     FROM `{$sectionTable}`
                     WHERE `{$sectionTable}`.`id_main_document`=?
                  )
                  ORDER BY `{$commentTable}`.`id`";
        $result = ParametrizedQuery::getSimpleArray($query, [$id]);
        return $result ? $result : null;
    }
}