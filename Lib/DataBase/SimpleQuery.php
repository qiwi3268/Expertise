<?php


namespace Lib\DataBase;


final class SimpleQuery extends DataBase
{


    // Предназначен для выполнения запросов типа SELECT
    // Принимает параметры------------------------------------
    // query string : простой запрос к БД
    // Возвращает параметры-----------------------------------
    // array : ассоциативный массив
    //
    static public function getFetchAssoc(string $query): array
    {
        $result = parent::executeSimpleQuery($query);
        $arr = [];
        while ($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
        $result->free();
        return $arr;
    }


    // Используется, когда результат выборки данных содержит одно поле
    // Предназначен для выполнения запросов типа SELECT
    // Принимает параметры------------------------------------
    // query string : простой запрос к БД
    // Возвращает параметры-----------------------------------
    // array : индексный массив (без подмассивов)
    //
    static public function getSimpleArray(string $query): array
    {
        $result = parent::executeSimpleQuery($query);
        $arr = [];
        while ($row = $result->fetch_row()) {
            $arr[] = $row[0];
        }
        $result->free();
        return $arr;
    }


    // Используется для внесения изменений в БД
    // Предназначен для выполнения запросов типа INSERT UPDATE DELETE
    // Принимает параметры------------------------------------
    // query string : простой запрос к БД
    // Возвращает параметры-----------------------------------
    // int : id только созданной записи
    //
    static public function set(string $query): int
    {
        parent::executeSimpleQuery($query);
        return parent::$mysqli->insert_id;
    }
}