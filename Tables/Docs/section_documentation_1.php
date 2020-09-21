<?php


namespace Tables\Docs;

use Lib\Exceptions\DataBase as DataBaseEx;

use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;

use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'doc_section_documentation_1'</i>
 *
 */
final class section_documentation_1 implements Existent, Responsible
{

    static private string $tableName = 'doc_section_documentation_1';

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
}