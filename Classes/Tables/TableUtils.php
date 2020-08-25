<?php


// Класс предназначен для различных вспомогательных методов
//
class TableUtils{

    // Прежназначен для получения ассоциативного массива зависимого справочника, упакованного по id-главного справочника
    // возвращает данные во возрастанию столбца sort
    // Принимает параметры-----------------------------------
    // mainAssoc           array : ассоциативный массив главного справочника
    // corrTableName      string : название таблицы корреляции
    // mainTableName      string : название таблицы главного справочника
    // dependentTableName string : название таблицы зависимого справочника
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив зависимого справочника по id-главного справочника
    //
    static public function getActiveDependent_CORR_mainAssoc(array $mainAssoc,
                                                             string $corrTableName,
                                                             string $mainTableName,
                                                             string $dependentTableName):array {
    
        // Генерируем состав оператора IN по id-главного справочника
        $mainIds = [];
        foreach($mainAssoc as ['id' => $id]){
         $mainIds[] = $id;
        }
        $condition = implode(',', $mainIds);
        
        // corr - таблица корреляции
        $query = "SELECT `$mainTableName`.`id` AS `id_main`,
                       `$dependentTableName`.`id`,
                       `$dependentTableName`.`name`
                  FROM (SELECT *
                        FROM `$corrTableName` AS `misc`
                        WHERE `misc`.`id_main` IN ($condition)) AS `corr`
        
                  LEFT JOIN `$mainTableName`
                         ON (`corr`.`id_main`=`$mainTableName`.`id`)
                  LEFT JOIN `$dependentTableName`
                         ON (`corr`.`id_dependent`=`$dependentTableName`.`id`)
        
                  WHERE `$dependentTableName`.`is_active`=1
                  ORDER BY `$dependentTableName`.`sort` ASC";
        
        $result = SimpleQuery::getFetchAssoc($query);
        
        // Укладываем зависимый справочник по id главного справочника
        $arr = [];
        foreach($result as ['id_main' => $id_main, 'id' => $id, 'name' => $name]){
            $arr[$id_main][] = ['id' => $id, 'name' => $name];
        }
        
        return $arr;
    }
   
   
    // Предназначен для получения строки values формата (?, ?, ?, NULL) в зависимости от количества переданных элементов массива.
    // Если элемент null, то он удаляется из массива и в values записывается NULL
    // Принимает параметры-----------------------------------
    // &bindParams array : ссылка на массив параметров
    // Возвращает параметры----------------------------------
    // string : строка values формата (?, ?, ?, NULL)
    //
    static public function getValuesWithoutNull(array &$bindParams):string {
    
        $result = [];
        
        foreach($bindParams as $key => $value){
           
            if(is_null($value)){
                
                $result[] = 'NULL';
                unset($bindParams[$key]);
            }else{
                
                $result[] = '?';
            }
        }
        
        
        $result = implode(', ', $result);
        return "({$result})";
    }



}