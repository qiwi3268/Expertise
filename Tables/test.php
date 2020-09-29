<?php


namespace Tables;

use Lib\DataBase\SimpleQuery;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'test'</i>
 *
 */
class test
{

    static public function create(string $field1): int
    {
        $query = "INSERT INTO `test`
                    (`id`, `field1`)
                  VALUES
                    (NULL, ?)";
        return ParametrizedQuery::set($query, [$field1]);
    }

    static public function test1(): array
    {
        $query = "SELECT *
                  FROM `doc_application`
                  WHERE `id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [1267]);
    }

    static public function test2(): array
    {
        $query = "SELECT *
                  FROM `doc_application`
                  WHERE `id`=1267";
        return SimpleQuery::getFetchAssoc($query);
    }
}