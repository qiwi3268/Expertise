<?php


abstract class NavigationTable{
    
    static public function getCount(int $id_user):int {
        
        $section = static::getSection(); // Позднее статическое связывание
        $query = "SELECT count(*) $section";
        
        return ParametrizedQuery::getSimpleArray($query, [$id_user])[0];
    }
    
    static public function getAssoc(int $id_user):?array {
        
        $section = static::getSection();
        $query = "SELECT * $section";
        
        return ParametrizedQuery::getFetchAssoc($query, [$id_user]);
        
    }
    
    abstract static protected function getSection():string;
}