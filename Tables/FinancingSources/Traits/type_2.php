<?php


namespace Tables\FinancingSources\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Реализует методы для источника финансирования "Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК"
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait type_2
{

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

        $table = self::$tableName;

        $bindParams = [$id_main_document, $full_name, $INN, $KPP, $OGRN, $address, $location, $telephone, $email, $no_data, $percent];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `full_name`, `INN`, `KPP`, `OGRN`, `address`, `location`, `telephone`, `email`, `no_data`, `percent`, `date_creation`)
                 VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}
