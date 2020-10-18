<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Таблица: <i>'assigned_expert_section_documentation_1'</i>
 *
 */
final class assigned_expert_section_documentation_1
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_section id раздела
     * @param int $id_expert id эксперта
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_section, int $id_expert): int
    {
        $query = "INSERT INTO `assigned_expert_section_documentation_1`
                    (`id_section`, `id_expert`, `is_section_preparation_finished`)
                  VALUES
                    (?, ?, 0)";
        return ParametrizedQuery::set($query, [$id_section, $id_expert]);
    }


    /**
     * Предназначен для получения ассоциативных разделов с ФИО:
     * - last_name
     * - first_name
     * - middle_name<br>
     * экспертов, которые были назначены на раздел по его id
     *
     * @param int $id_section id раздела
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssocFIOByIdSection(int $id_section): array
    {
        $query = "SELECT `user`.`last_name`,
                         `user`.`first_name`,
                         `user`.`middle_name`
                  FROM `assigned_expert_section_documentation_1`
                  JOIN `user`
                     ON (`assigned_expert_section_documentation_1`.`id_expert`=`user`.`id`)
                  WHERE `assigned_expert_section_documentation_1`.`id_section`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id_section]);
    }
}