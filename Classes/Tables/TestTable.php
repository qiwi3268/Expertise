<?php


final class TestTable{


    static public function getFetch(){

        $query = "SELECT *
				  FROM `test`";
        return SimpleQuery::getFetchAssoc($query);
    }

    static public function addRow(){

        $query = "INSERT INTO `test` (`id`, `id2`, `id3`, `id4`) VALUES ('1', '2', '3', '4')";
        SimpleQuery::getFetchAssoc($query);
    }

    static public function create():int {

        $val = '15';

        $query = "INSERT INTO `test` (`id`, `id2`, `id3`, `id4`) VALUES (?, '2', '3', '4')";

        return ParametrizedQuery::set($query, [$val], 'i');
    }

}