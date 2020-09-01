<?php


namespace Tables\FinancingSources;

use Lib\DataBase\ParametrizedQuery;


// Источники финансирования
// Бюджетные средства
class type_1
{

    static private string $tableName = 'financing_source_type_1';

    use Traits\Deleter;


    // Предназначен для получения ассоциативного массива источников финансирования по id заявления
    // Принимает параметры-----------------------------------
    // id_application int : id заявления
    // Возвращает параметры----------------------------------
    // array : в случае, если источники финансирования существуют
    // null  : в противном случае
    //
    static public function getAssocByIdApplication(int $id_application): ?array
    {
        $query = "SELECT `financing_source_type_1`.`id`,
                         `misc_budget_level`.`name` AS `name_budget_level`,
                         `financing_source_type_1`.`no_data`,
                         `financing_source_type_1`.`percent`
                  FROM (SELECT * FROM `financing_source_type_1`
                        WHERE `financing_source_type_1`.`id_application`=?) AS `financing_source_type_1`
                  LEFT JOIN (`misc_budget_level`)
                        ON (`financing_source_type_1`.`id_budget_level`=`misc_budget_level`.`id`)";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_application]);
        return $result ? $result : null;
    }


    // Предназначен для создания записи инсточника финансирования
    // Принимает параметры-----------------------------------
    // * согласно таблице financing_source_type_1
    // Возвращает параметры----------------------------------
    // id int : id созданной записи
    //
    static public function create(int $id_application, ?int $id_budget_level, int $no_data, ?int $percent): int
    {
        $bindParams = [$id_application, $id_budget_level, $no_data, $percent];
        $values = \Tables\Helpers\Helper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `financing_source_type_1`
                    (`id_application`, `id_budget_level`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}
