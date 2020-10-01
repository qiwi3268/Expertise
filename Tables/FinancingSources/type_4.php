<?php


namespace Tables\FinancingSources;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Таблица: <i>'application_financing_source_type_4'</i>
 *
 * Средства инвестора
 *
 */
final class type_4 implements Interfaces\FinancingSourceTable
{

    static private string $tableName = 'application_financing_source_type_4';

    use Traits\Deleter;
    use Traits\FinancingSourceTable;


    /**
     * Предназначен для создания записи источника финансирования
     *
     * @param int $id_main_document
     * @param int $no_data
     * @param int|null $percent
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        int $no_data,
        ?int $percent
    ): int {
        $bindParams = [$id_main_document, $no_data, $percent];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `application_financing_source_type_4`
                    (`id_main_document`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}