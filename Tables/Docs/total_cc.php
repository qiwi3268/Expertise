<?php


namespace Tables\Docs;

use Lib\Exceptions\DataBase as DataBaseEx;

use Tables\Docs\Interfaces\Responsible;
use Tables\CommonInterfaces\Existent;

use Tables\Docs\Traits\Responsible as ResponsibleTrait;
use Tables\CommonTraits\Existent as ExistentTrait;

use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'doc_total_cc'</i>
 *
 */
final class total_cc implements Existent, Responsible
{

    static private string $tableName = 'doc_total_cc';

    use ExistentTrait;
    use ResponsibleTrait;


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
                    (`id_main_document`, `id_author`, `responsible_type`, `date_creation`)
                  VALUES
                    (?, ?, 'type_4', UNIX_TIMESTAMP())";
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
}