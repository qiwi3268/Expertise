<?php


namespace Tables\FinancingSources;

use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


// Источники финансирования
// Средства инвестора
class type_4
{

    static private string $tableName = 'financing_source_type_4';

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
        $query = "SELECT *
                  FROM `financing_source_type_4`
                  WHERE `id_application`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_application]);
        return $result ? $result : null;
    }


    // Предназначен для создания записи инсточника финансирования
    // Принимает параметры-----------------------------------
    // * согласно таблице financing_source_type_4
    // Возвращает параметры----------------------------------
    // id int : id созданной записи
    //
    static public function create(int $id_application, int $no_data, ?int $percent): int
    {
        $bindParams = [$id_application, $no_data, $percent];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `financing_source_type_4`
                    (`id_application`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}