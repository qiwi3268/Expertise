<?php


namespace Tables\Docs;

use Lib\Exceptions\DataBase as DataBaseEx;

use Lib\DataBase\ParametrizedQuery;

use Tables\Docs\Interfaces\Document;
use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\Document as DocumentTrait;
use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'doc_section_documentation_1'</i>
 *
 */
final class section_documentation_1 implements Document, Existent, Responsible
{

    static private string $tableName = 'doc_section_documentation_1';
    static private string $stageTableName = 'stage_section_documentation_1';

    use DocumentTrait;
    use ExistentTrait;
    use ResponsibleTrait;


    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int $id_main_block
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_main_block): int
    {
        $query = "INSERT INTO `doc_section_documentation_1`
                    (`id_main_document`, `id_main_block`, `responsible_type`,`date_creation`)
                  VALUES
                    (?, ?, 'type_4', UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_main_document, $id_main_block]);
    }


    /**
     * Предназначен для получения name и short_name блока из 341 приказа по id записи раздела
     *
     *
     * @param int $id id записи
     * @return array
     * @throws DataBaseEx
     */
    static public function getNameAndShortNameMainBlockById(int $id): array
    {
        $query = "SELECT `main_block_341_documentation_1`.`name`,
                         `main_block_341_documentation_1`.`short_name`
                  FROM `doc_section_documentation_1`
                  JOIN (`main_block_341_documentation_1`)
                     ON (`doc_section_documentation_1`.id_main_block = `main_block_341_documentation_1`.`id`)
                  WHERE `doc_section_documentation_1`.`id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id])[0];
    }
}