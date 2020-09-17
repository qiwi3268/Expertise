<?php


namespace Tables\order_341\documentation_1;

use Lib\DataBase\ParametrizedQuery;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Таблица: <i>'assigned_expert_main_block_341_documentation_1'</i>
 *
 */
final class assigned_expert
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_total_cc
     * @param int $id_main_block
     * @param int $id_expert
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_total_cc, int $id_main_block, int $id_expert): int
    {
        $query = "INSERT INTO `assigned_expert_main_block_341_documentation_1`
                    (`id_total_cc`, `id_main_block`, `id_expert`)
                  VALUES
                    (?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_total_cc, $id_main_block, $id_expert]);
    }
}