<?php


namespace Tables\FinancingSources;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Таблица: <i>'application_financing_source_type_2'</i>
 *
 * Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК
 *
 */
final class type_2 implements Interfaces\FinancingSourceTable
{

    static private string $tableName = 'application_financing_source_type_2';

    use Traits\Deleter;
    use Traits\FinancingSourceTable;


    /**
     * Предназначен для создания записи источника финансирования
     *
     * @param int $id_main_document
     * @param string|null $full_name
     * @param string|null $INN
     * @param string|null $KPP
     * @param string|null $OGRN
     * @param string|null $address
     * @param string|null $location
     * @param string|null $telephone
     * @param string|null $email
     * @param int $no_data
     * @param int|null $percent
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        ?string $full_name,
        ?string $INN,
        ?string $KPP,
        ?string $OGRN,
        ?string $address,
        ?string $location,
        ?string $telephone,
        ?string $email,
        int $no_data,
        ?int $percent
    ): int {
        $bindParams = [$id_main_document, $full_name, $INN, $KPP, $OGRN, $address, $location, $telephone, $email, $no_data, $percent];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `application_financing_source_type_2`
                    (`id_main_document`, `full_name`, `INN`, `KPP`, `OGRN`, `address`, `location`, `telephone`, `email`, `no_data`, `percent`, `date_creation`)
                 VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }

}