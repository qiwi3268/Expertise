<?php


namespace Tables\FinancingSources\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Реализует методы для источника финансирования "Собственные средства застройщика"
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait type_3
{

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

        $table = self::$tableName;

        $bindParams = [$id_main_document, $no_data, $percent];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}
