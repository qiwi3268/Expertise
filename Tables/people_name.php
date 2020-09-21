<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'people_name'</i>
 *
 */
final class people_name
{

    /**
     * Предназначен для создания записи в таблице имен
     *
     * @param string $name имя
     * @throws DataBaseEx
     */
    static public function create(string $name): void
    {
        $query = "INSERT INTO `people_name`
                    (`name`)
                  VALUES
                    (?)";
        ParametrizedQuery::set($query, [$name]);
    }


    /**
     * Предназначен для полученя простого массива имен
     *
     * @return array индексный массив с именами
     * @throws DataBaseEx
     */
    static public function getAllSimple(): array
    {
        $query = "SELECT `name`
                  FROM `people_name`";
        return SimpleQuery::getSimpleArray($query);
    }


    /**
     * Предназначен для получения ассоциативных массивов имен
     *
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssoc(): array
    {
        $query = "SELECT *
                  FROM `people_name`";
        return SimpleQuery::getFetchAssoc($query);
    }


    /**
     * Предназначен для обновления записи имени по её id
     *
     * @param int $id id записи
     * @param string $name новое значение имени
     * @throws DataBaseEx
     */
    static public function updateNameById(int $id, string $name): void
    {
        $query = "UPDATE `people_name`
                  SET `name`=?
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$name, $id]);
    }
}