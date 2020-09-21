<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'assigned_expert_total_cc'</i>
 *
 */
final class assigned_expert_total_cc
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_total_cc id сводного замечания / заключения
     * @param int $id_expert id эксперта
     * @param int $is_lead ведущий ли эксперт
     * @param int $is_common_part назначен ли эксперт на общую часть
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_total_cc, int $id_expert, int $is_lead, int $is_common_part): int
    {
        $query = "INSERT INTO `assigned_expert_total_cc`
                    (`id_total_cc`, `id_expert`, `is_lead`, `is_common_part`)
                  VALUES
                    (?, ?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_total_cc, $id_expert, $is_lead, $is_common_part]);
    }
}