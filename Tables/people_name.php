<?php


namespace Tables;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


final class people_name
{

    // Предназначен для создания записи в таблице имен
    // Принимает параметры-----------------------------------
    // name string : имя
    //
    static public function create(string $name): void
    {
        $query = "INSERT INTO `people_name`
                    (`name`)
                  VALUES
                    (?)";
        ParametrizedQuery::set($query, [$name]);
    }


    // Предназначен для полученя простого массива имен
    // Возвращает параметры-----------------------------------
    // array : простой массив имен
    //
    static public function getNames(): array
    {
        $query = "SELECT `name`
                  FROM `people_name`";
        return SimpleQuery::getSimpleArray($query);
    }

    static public function getAll(): array {
        $query = "SELECT *
                  FROM `people_name`";
        return SimpleQuery::getFetchAssoc($query);
    }

    static public function updateName(int $id, string $name): void
    {
        $query = "UPDATE `people_name`
                  SET `name`=?
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$name, $id]);
    }
}