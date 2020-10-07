<?php


namespace History;

use Lib\Exceptions\DataBase as DataBaseEx;

use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Таблица: <i>'history_application'</i>
 *
 */
class application
{
    //todo implements

    static private string $tableName = 'history_application';


    /**
     * Предназначен для создания записи в таблице истории к документу
     *
     * @param int $id_main_document id главного документа
     * @param int|null $id_author id автора
     * @param string $description описание действия
     * @param string $additional_information дополнительная информация
     * @param int $is_visible_from_applicant флаг видимости истории для заявителя
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        ?int $id_author,
        string $description,
        string $additional_information,
        int $is_visible_from_applicant
    ): int {

        $table = self::$tableName;

        $bindParams = [$id_main_document, $id_author, $description, $additional_information, $is_visible_from_applicant];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_author`, `description`, `additional_information`, `is_visible_from_applicant`, `date_creation`)
                  VALUES
                     ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query,  $bindParams);
    }
}