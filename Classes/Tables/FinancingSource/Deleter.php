<?php


namespace Classes\Tables\FinancingSource;


trait Deleter{
    
    
    static public function deleteAllByIdApplication(int $id_application):void {
        
        $table = self::$tableName;
        
        $query = "DELETE
                  FROM $table
                  WHERE `id_application`=?";
        
        \ParametrizedQuery::set($query, [$id_application]);
    }
}
