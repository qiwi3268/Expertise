<?php


final class ParametrizedQuery extends DataBase{

    // Все методы для обработки параметризованных запросов принимают параметры:
    // (в соотстветствии с DataBase::executeParametrizedQuery)
    //
    // query            string: параметризованный запрос к БД
    // bindParams       array : параметры запроса


    // Предназначен для выполнения запросов типа SELECT
    // Возвращает параметры-----------------------------------
    // array : ассоциативный массив
    //
    static public function getFetchAssoc(string $query, array $bindParams):array {

        $result = parent::executeParametrizedQuery($query, $bindParams);
        $arr = [];
        while($row = $result->fetch_assoc()){
            $arr[]  = $row;
        }
        $result->free();
        return $arr;
    }


    // Используется, когда результат выборки данных содержит одно поле
    // Предназначен для выполнения запросов типа SELECT
    // Возвращает параметры-----------------------------------
    // array : индексный массив (без подмассивов)
    //
    static public function getSimpleArray(string $query, array $bindParams):array {

        $result = parent::executeParametrizedQuery($query, $bindParams);
        $arr = [];
        while($row = $result->fetch_row()){
            $arr[]  = $row[0];
        }
        $result->free();
        return $arr;
    }


    // Используется для внесения изменений в БД
    // Предназначен для выполнения запросов типа INSERT UPDATE DELETE
    // Возвращает параметры-----------------------------------
    // int : id только созданной записи
    //
    static public function set(string $query, array $bindParams):int {

        parent::executeParametrizedQuery($query, $bindParams);
        return parent::$mysqli->insert_id;
    }
}
