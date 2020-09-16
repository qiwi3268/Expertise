<?php


namespace Tables\Docs;

use Lib\DataBase\ParametrizedQuery;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Таблица: <i>'doc_total_cc'</i>
 *
 */
class total_cc
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_application
     * @param int $id_author
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_application, int $id_author): int
    {
        $query = "INSERT INTO `doc_total_cc`
                    (`id_application`, `id_author`, `date_creation`)
                  VALUES
                    (?, ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_application, $id_author]);
    }
}