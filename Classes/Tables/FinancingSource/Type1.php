<?php


namespace Classes\Tables\FinancingSource;


// Источники финансирования
// Бюджетные средства
class Type1{
    
<<<<<<< HEAD
    //static private string $tableName = 'financing_source_type_1';
=======
    static private string $tableName = 'financing_source_type_1';
    
    use Deleter;
    // deleteAllByIdApplication(int $id_application):void
>>>>>>> 346c3228d8d85e51138fbddaff8753f22b7e3ce0
    
    
    // Предназначен для получения ассоциативного массива источников финансирования по id заявления
    // Принимает параметры-----------------------------------
    // id_application int : id заявления
    // Возвращает параметры----------------------------------
    // array : в случае, если источники финансирования существуют
    // null  : в противном случае
    //
    static public function getAssocByIdApplication(int $id_application):?array {
        
        $query = "SELECT `financing_source_type_1`.`id`,
                         `misc_budget_level`.`name` AS `name_budget_level`,
                         `financing_source_type_1`.`no_data`,
                         `financing_source_type_1`.`percent`
                  FROM (SELECT * FROM `financing_source_type_1`
                        WHERE `financing_source_type_1`.`id_application`=?) AS `financing_source_type_1`
                  LEFT JOIN (`misc_budget_level`)
                        ON (`financing_source_type_1`.`id_budget_level`=`misc_budget_level`.`id`)";
        
        $result = \ParametrizedQuery::getFetchAssoc($query, [$id_application]);
        
        return $result ? $result : null;
    }
    
    
    // Предназначен для создания записи инсточника финансирования
    // Принимает параметры-----------------------------------
    // * согласно таблице financing_source_type_1
    // Возвращает параметры----------------------------------
    // id int : id созданной записи
    //
<<<<<<< HEAD
    static public function create(int $id_application, int $id_budget_level, int $no_data, ?int $percent):int {
=======
    static public function create(int $id_application, ?int $id_budget_level, int $no_data, ?int $percent):int {
>>>>>>> 346c3228d8d85e51138fbddaff8753f22b7e3ce0
        
        $bindParams = [$id_application, $id_budget_level, $no_data, $percent];
        $values = \TableUtils::getValuesWithoutNull($bindParams);
        
        $query = "INSERT INTO `financing_source_type_1`
<<<<<<< HEAD
                    (`id_application`, `id_budget_level`, `no_data`, `percent`)
                  VALUES {$values}";
=======
                    (`id_application`, `id_budget_level`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
>>>>>>> 346c3228d8d85e51138fbddaff8753f22b7e3ce0
        return \ParametrizedQuery::set($query, $bindParams);
    }
}
