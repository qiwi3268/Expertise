<?php


namespace Classes\Tables\FinancingSource;


// Источники финансирования
// Собственные средства застройщика
class Type3{
    
<<<<<<< HEAD
=======
    static private string $tableName = 'financing_source_type_3';
    
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
        
        $query = "SELECT *
                  FROM `financing_source_type_3`
                  WHERE `id_application`=?";
        
        $result = \ParametrizedQuery::getFetchAssoc($query, [$id_application]);
        
        return $result ? $result : null;
    }
    
    
    // Предназначен для создания записи инсточника финансирования
    // Принимает параметры-----------------------------------
    // * согласно таблице financing_source_type_3
    // Возвращает параметры----------------------------------
    // id int : id созданной записи
    //
    static public function create(int $id_application, int $no_data, ?int $percent):int {
        
        $bindParams = [$id_application, $no_data, $percent];
        $values = \TableUtils::getValuesWithoutNull($bindParams);
        
        $query = "INSERT INTO `financing_source_type_3`
<<<<<<< HEAD
                    (`id_application`, `no_data`, `percent`)
                  VALUES {$values}";
=======
                    (`id_application`, `no_data`, `percent`, `date_creation`)
                  VALUES ({$values}, UNIX_TIMESTAMP())";
>>>>>>> 346c3228d8d85e51138fbddaff8753f22b7e3ce0
        
        return \ParametrizedQuery::set($query, $bindParams);
    }
}