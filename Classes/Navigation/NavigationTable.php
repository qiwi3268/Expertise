<?php


use Lib\DataBase\ParametrizedQuery;


// Абстрактный класс предназначен для получения количества строк в выборке (для навигационного сайдбара) и ассоциативного
// массива для отображения данных во view
//
abstract class NavigationTable{
    
    
    // Предназначен для получения количество строк в выборке с динамической секцией FROM, WHERE по id пользователя
    // Принимает параметры-----------------------------------
    // id_user         int : id пользователя
    // Возвращает параметры----------------------------------
    // int : количество строк в выборке
    //
    static public function getCountByIdUser(int $id_user):int {
        
        $section = static::getSection(); // Позднее статическое связывание
        $query = "SELECT count(*) $section";
        
        return ParametrizedQuery::getSimpleArray($query, [$id_user])[0];
    }
    
    
    // Предназначен для получение ассоциативного массива с динамической секцией FROM, WHERE по id пользователя
    // В зависимости от дочернего класса, в котором вызывается этот метод, будет передана своя уникальная секция
    // Принимает параметры-----------------------------------
    // id_user         int : id пользователя
    // SORT_name    string : имя столбца для сортировки
    // SORT_type    string : тип сортировки (ASC/DESC)
    // LIMIT_offset    int : смещение выборки на указанное количество элементов
    // LIMIT_row_count int : количество строк в выборке
    // Возвращает параметры----------------------------------
    // array : в случае, если данные по запросу существуют
    // null  : в противном случае
    //
    static public function getAssocByIdUser(int $id_user,
                                            string $SORT_name,
                                            string $SORT_type,
                                            int $LIMIT_offset,
                                            int $LIMIT_row_count):?array {
        
        $section = static::getSection();
        $query = "SELECT *
                  $section
                  ORDER BY `{$SORT_name}` $SORT_type
                  LIMIT $LIMIT_offset, $LIMIT_row_count";
        
        return ParametrizedQuery::getFetchAssoc($query, [$id_user]);
    }
    
    
    // Абстрактный метод для получения динамической секции FROM, WHERE
    abstract static protected function getSection():string;
}