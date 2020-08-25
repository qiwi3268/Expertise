<?php


namespace Classes\Tables\FinancingSource;


// Источники финансирования
// Собственные средства застройщика
class Type3{
    
    
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
                    (`id_application`, `no_data`, `percent`)
                  VALUES {$values}";
        
        return \ParametrizedQuery::set($query, $bindParams);
    }
}