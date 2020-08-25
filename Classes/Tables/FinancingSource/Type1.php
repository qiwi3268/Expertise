<?php


namespace Classes\Tables\FinancingSource;

class Type1{
    
    static private string $tableName = 'finansing_source_type_1';
    
    static public function getAssocByIdApplication(int $id_application):?array {
        
        $query = "SELECT `finansing_source_type_1`.`id`,
                         `misc_budget_level`.`name` AS `name_budget_level`,
                         `finansing_source_type_1`.`no_data`,
                         `finansing_source_type_1`.`percent`
                  FROM (SELECT * FROM `finansing_source_type_1`
                        WHERE `finansing_source_type_1`.`id_application`=?) AS `finansing_source_type_1`
                  LEFT JOIN (`misc_budget_level`)
                        ON (`finansing_source_type_1`.`id_budget_level`=`misc_budget_level`.`id`)";
        
        $result = \ParametrizedQuery::getFetchAssoc($query, [$id_application]);
        
        return $result ? $result : null;
    }
    
    static public function create(int $id_application, int $id_budget_level, int $no_data, ?int $percent):int {
        
        $bindParams = [$id_application, $id_budget_level, $no_data, $percent];
        $values = \TableUtils::getValuesWithoutNullValues($bindParams);
        
        $query = "INSERT INTO `finansing_source_type_1`
                    (`id_application`, `id_budget_level`, `no_data`, `percent`)
                  VALUES (?, ?, ?, ?)";
        return \ParametrizedQuery::set($query, [$id_application, $id_budget_level, $no_data, $percent]);
    }
    
}
