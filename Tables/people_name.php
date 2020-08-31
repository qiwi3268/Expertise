<?php


namespace Tables;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


class people_name
{

    static public function create(string $name): void
    {
        $query = "INSERT INTO `people_name`
                    (`name`)
                  VALUES
                    (?)";
        ParametrizedQuery::set($query, [$name]);
    }


    static public function getNames(): array
    {
        $query = "SELECT `name`
                  FROM `people_name`";
        return SimpleQuery::getSimpleArray($query);
    }
}