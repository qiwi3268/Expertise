<?php


namespace Tables\FinancingSources;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Таблица: <i>'financing_source_type_3'</i>
 *
 * Собственные средства застройщика
 *
 */
final class type_3 implements Interfaces\FinancingSourceTable
{

    static private string $tableName = 'financing_source_type_3';

    use Traits\Deleter;
    use Traits\FinancingSourceTable;


    /**
     * Предназначен для создания записи источника финансирования
     *
     * @param int $id_application
     * @param int $no_data
     * @param int|null $percent
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(
        int $id_application,
        int $no_data,
        ?int $percent
    ): int {
        $bindParams = [$id_application, $no_data, $percent];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `financing_source_type_3`
                    (`id_application`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}