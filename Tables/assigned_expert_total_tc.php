<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Таблица: <i>'assigned_expert_total_tc'</i>
 *
 */
class assigned_expert_total_tc
{


    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_total_tc
     * @param int $id_expert
     * @param int $is_lead
     * @param int $is_common_part
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_total_tc, int $id_expert, int $is_lead, int $is_common_part): int
    {
        $query = "INSERT INTO `assigned_expert_total_cc`
                    (`id_total_cc`, `id_expert`, `is_lead`, `is_common_part`)
                  VALUES
                    (?, ?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_total_tc, $id_expert, $is_lead, $is_common_part]);
    }
}