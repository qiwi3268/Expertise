<?php


// Трейт, предназначений для реализации общих методов одиночных справочников
//
trait Trait_singleMiscTable{

   // Предназначен для получения ассициативного массива справочника,
   // возвращает активные записи
   // возвращает данные по возрастанию столбца sort
   // Возвращает параметры-----------------------------------
   // array : ассоциативный массив справочника
   //
   static public function getAllActive():array {

      $table = self::$tableName;

      $query = "SELECT `id`,
                       `name`
                  FROM $table
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";

      return SimpleQuery::getFetchAssoc($query);
   }
}

