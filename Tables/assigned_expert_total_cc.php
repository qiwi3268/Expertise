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


    /**
     * Предназначен для проверки существования записи по id сводного замечания / заключения
     * и id эксперта
     *
     * Фактически метод предназначен для проверки назначен ли эксперт на сводного замечание / заключение
     *
     *
     * @param int $id_total_cc id сводного замечания / заключения
     * @param int $id_expert id эксперта
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     * @throws DataBaseEx
     */
    static public function checkExistByIdTotalCCAndIdExpert(int $id_total_cc, int $id_expert): bool
    {
        $query = "SELECT count(*)>0
                  FROM `assigned_expert_total_cc`
                  WHERE `id_total_cc`=? AND `id_expert`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_total_cc, $id_expert])[0];
    }


    /**
     * Предназначен для проверки является ли пользователь ведущим экспертом
     *
     * @param int $id_total_cc id сводного замечания / заключения
     * @param int $id_expert id эксперта
     * @return bool <b>true</b> пользователь является ведущим экспертом<br>
     * <b>false</b> в противном случае
     * @throws DataBaseEx
     */
    static public function checkLeadByIdTotalCCAndIdExpert(int $id_total_cc, int $id_expert): bool
    {
        $query = "SELECT count(*)>0
                  FROM `assigned_expert_total_cc`
                  WHERE `id_total_cc`=? AND `id_expert`=? AND `is_lead`=1";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_total_cc, $id_expert])[0];
    }


    /**
     * Предназначен для проверки является ли пользователь экспертом, назначенным на общую часть
     *
     * @param int $id_total_cc id сводного замечания / заключения
     * @param int $id_expert id эксперта
     * @return bool <b>true</b> пользователь является экспертом, назначенным на общую часть<br>
     * <b>false</b> в противном случае
     * @throws DataBaseEx
     */
    static public function checkCommonPartByIdTotalCCAndIdExpert(int $id_total_cc, int $id_expert): bool
    {
        $query = "SELECT count(*)>0
                  FROM `assigned_expert_total_cc`
                  WHERE `id_total_cc`=? AND `id_expert`=? AND `is_common_part`=1";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_total_cc, $id_expert])[0];
    }


    /**
     * Предназначен для получения ассоциативного массива пользователя, который является
     * ведущим экспертом на сводном замечании / заключении
     *
     * @param int $id_total_cc id сводного замечания / заключения
     * @return array ассоциативный массив
     * @throws DataBaseEx
     */
    static public function getAssocExpertWhereLeadByIdTotalCC(int $id_total_cc): array
    {
        $query = "SELECT `user`.`id` as `user_id`,
                         `user`.`last_name`,
	                     `user`.`first_name`,
                         `user`.`middle_name`
                 FROM `assigned_expert_total_cc`
                 JOIN `user`
                    ON (`assigned_expert_total_cc`.`id_expert`=`user`.`id`)
                 WHERE `assigned_expert_total_cc`.`id_total_cc`=? AND `assigned_expert_total_cc`.`is_lead`=1";
        return ParametrizedQuery::getFetchAssoc($query, [$id_total_cc])[0];
    }


    /**
     * Предназначен для получения ассоциативных массивов пользователей, которые являются
     * экспертами, назначенными на общую часть, на сводном замечании / заключении
     *
     * @param int $id_total_cc id сводного замечания / заключения
     * @return array индексный массив с ассоциативными массива внутри
     * @throws DataBaseEx
     */
    static public function getAllAssocExpertWhereCommonPartByIdTotalCC(int $id_total_cc): array
    {
        $query = "SELECT `user`.`id` as `user_id`,
                         `user`.`last_name`,
	                     `user`.`first_name`,
                         `user`.`middle_name`
                 FROM `assigned_expert_total_cc`
                 JOIN `user`
                    ON (`assigned_expert_total_cc`.`id_expert`=`user`.`id`)
                 WHERE `assigned_expert_total_cc`.`id_total_cc`=? AND `assigned_expert_total_cc`.`is_common_part`=1";
        return ParametrizedQuery::getFetchAssoc($query, [$id_total_cc]);
    }
}