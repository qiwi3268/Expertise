<?php


namespace Tables\FinancingSources;

use Lib\DataBase\ParametrizedQuery;


// Источники финансирования
// Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК
class type_2
{

    static private string $tableName = 'financing_source_type_2';

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
                  FROM `financing_source_type_2`
                  WHERE `id_application`=?";

        $result = ParametrizedQuery::getFetchAssoc($query, [$id_application]);

        return $result ? $result : null;
    }


    // Предназначен для создания записи инсточника финансирования
    // Принимает параметры-----------------------------------
    // * согласно таблице financing_source_type_2
    // Возвращает параметры----------------------------------
    // id int : id созданной записи
    //
    static public function create(
        int $id_application,
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

        $bindParams = [$id_application, $full_name, $INN, $KPP, $OGRN, $address, $location, $telephone, $email, $no_data, $percent];
        $values = \Tables\Helpers\Helper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `financing_source_type_2`
                    (`id_application`, `full_name`, `INN`, `KPP`, `OGRN`, `address`, `location`, `telephone`, `email`, `no_data`, `percent`, `date_creation`)
                 VALUES ({$values}, UNIX_TIMESTAMP())";

        return ParametrizedQuery::set($query, $bindParams);
    }

}