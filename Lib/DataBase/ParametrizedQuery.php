<?php


namespace Lib\DataBase;


/**
 * Предназначен для работы с параметризованными запросами к базе данных
 *
 */
final class ParametrizedQuery extends DataBase
{

    /**
     * Предназначен для выполнения запросов типа SELECT
     *
     * @param string $query параметризованный запрос к БД
     * @param array $bindParams параметры запроса
     * @return array ассоциативный массив результата запроса
     * @throws \Lib\Exceptions\DataBase
     */
    static public function getFetchAssoc(string $query, array $bindParams): array
    {
        $result = parent::executeParametrizedQuery($query, $bindParams);
        $arr = [];
        while ($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
        $result->free();
        return $arr;
    }


    /**
     * Предназначен для выполнения запросов типа SELECT
     *
     * Используется, когда результат выборки данных содержит одно поле
     *
     * @param string $query параметризованный запрос к БД
     * @param array $bindParams параметры запроса
     * @return array индексный массив результата запроса (без подмассивов)
     * @throws \Lib\Exceptions\DataBase
     */
    static public function getSimpleArray(string $query, array $bindParams): array
    {
        $result = parent::executeParametrizedQuery($query, $bindParams);
        $arr = [];
        while ($row = $result->fetch_row()) {
            $arr[] = $row[0];
        }
        $result->free();
        return $arr;
    }


    /**
     * Используется для внесения изменений в БД
     *
     * Предназначен для выполнения запросов типа INSERT UPDATE DELETE
     *
     * @param string $query параметризованный запрос к БД
     * @param array $bindParams параметры запроса
     * @return int id созданной записи
     * @throws \Lib\Exceptions\DataBase
     */
    static public function set(string $query, array $bindParams): int
    {
        parent::executeParametrizedQuery($query, $bindParams);
        return parent::$mysqli->insert_id;
    }
}
