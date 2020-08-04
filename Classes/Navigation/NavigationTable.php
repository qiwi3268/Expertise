<?php


abstract class NavigationTable{
    
    static public function getCount(int $id_user):int {
        
        $section = static::getSection(); // Позднее статическое связывание
        $query = "SELECT count(*) $section";
        
        return ParametrizedQuery::getSimpleArray($query, [$id_user])[0];
    }
    
    //
    // id_user         int : id пользователя
    // LIMIT_offset    int :
    // LIMIT_row_count int :
    //
    static public function getAssoc(int $id_user, int $LIMIT_offset, int $LIMIT_row_count):?array {
        
        $section = static::getSection();
        $query = "SELECT *
                  $section
                  LIMIT $LIMIT_offset, $LIMIT_row_count";
        
        return ParametrizedQuery::getFetchAssoc($query, [$id_user]);
        
    }
    
    abstract static protected function getSection():string;
}