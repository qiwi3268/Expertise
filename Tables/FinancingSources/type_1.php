<?php


namespace Tables\FinancingSources;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;

use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Таблица: <i>'application_financing_source_type_1'</i>
 *
 * Бюджетные средства
 *
 */
final class type_1 implements Interfaces\FinancingSourceTable
{

    static private string $tableName = 'application_financing_source_type_1';

    use Traits\Deleter;


    /**
     * Реализация метода интерфейса
     * {@see \Tables\FinancingSources\Interfaces\FinancingSourceTable::getAllAssocByIdMainDocument()}
     *
     * @param int $id_main_document
     * @return array|null
     * @throws DataBaseEx
     * @throws TablesEx
     */
    static public function getAllAssocByIdMainDocument(int $id_main_document): ?array
    {
        $query = "SELECT `financing_source_type_1`.`id`,
       
                         `financing_source_type_1`.`id_budget_level`,
                         `misc_budget_level`.`name` AS `name_budget_level`,
       
                         `financing_source_type_1`.`no_data`,
                         `financing_source_type_1`.`percent`
                  FROM (SELECT * FROM `application_financing_source_type_1`
                        WHERE `application_financing_source_type_1`.`id_main_document`=?) AS `financing_source_type_1`
                  LEFT JOIN (`misc_budget_level`)
                        ON (`financing_source_type_1`.`id_budget_level`=`misc_budget_level`.`id`)";
        $results = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);

        if ($results) {

            // Перекладываем данные о справочнике в отдельный подмассив
            foreach ($results as &$result) {

                TableHelper::restructureMiscToSubarray($result, 'id_budget_level', 'name_budget_level', 'budget_level');
            }
            unset($result);

            return $results;
        }
        return null;
    }


    /**
     * Предназначен для создания записи источника финансирования
     *
     * @param int $id_application
     * @param int|null $id_budget_level
     * @param int $no_data
     * @param int|null $percent
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(
        int $id_application,
        ?int $id_budget_level,
        int $no_data,
        ?int $percent
    ): int {
        $bindParams = [$id_application, $id_budget_level, $no_data, $percent];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `application_financing_source_type_1`
                    (`id_application`, `id_budget_level`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}
