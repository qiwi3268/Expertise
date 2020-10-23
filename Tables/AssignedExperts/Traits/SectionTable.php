<?php


namespace Tables\AssignedExperts\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;



/**
 * Реализует общие методы для таблиц назначенных экспертов на раздел
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait SectionTable
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
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
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
        $table = self::$tableName;

        $query = "SELECT `user`.`last_name`,
                         `user`.`first_name`,
                         `user`.`middle_name`
                  FROM `{$table}`
                  JOIN `user`
                     ON (`{$table}`.`id_expert`=`user`.`id`)
                  WHERE `{$table}`.`id_section`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id_section]);
    }


    /**
     * Предназначен для получения простого массива id назначенных на раздел экспертов по id раздела
     *
     * @param int $id_section id раздела
     * @return array индексный массив
     * @throws DataBaseEx
     */
    static public function getExpertIdsByIdSection(int $id_section): array
    {
        $table = self::$tableName;

        $query = "SELECT `id_expert`
                  FROM `{$table}`
                  WHERE `id_section`=?";
        return ParametrizedQuery::getSimpleArray($query, [$id_section]);
    }


    /**
     * Предназначен для получения простого массива id назначенных на раздел экспертов, которы не закончили работу
     * по подготовке раздела по id раздела
     *
     * @param int $id_section id раздела
     * @return array|null<b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getExpertsIdsWhereNotSectionPreparationFinishedByIdSection(int $id_section): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `id_expert`
                  FROM `{$table}`
                  WHERE `id_section`=? AND `is_section_preparation_finished`='0'";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_section]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для получения количества записей назначенных экспертов,
     * которые не закончили работу по подготовке раздела по id раздела
     *
     * @param int $id_section id раздела
     * @return int количество записей
     * @throws DataBaseEx
     */
    static public function getCountWhereNotSectionPreparationFinishedByIdSection(int $id_section): int
    {
        $table = self::$tableName;

        $query = "SELECT COUNT(*)
                  FROM `{$table}`
                  WHERE `id_section`=? AND `is_section_preparation_finished`='0'";
        return ParametrizedQuery::getSimpleArray($query, [$id_section])[0];
    }



    /**
     * Предназначен для установки флага, что работа по подготовке раздела закочена
     *
     * @param int $id_section id раздела
     * @param int $id_expert id эксперта
     * @throws DataBaseEx
     */
    static public function setSectionPreparationFinishedByIdSectionAndIdExpert(int $id_section, int $id_expert): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_section_preparation_finished`='1'
                  WHERE `id_section`=? AND `id_expert`=?";
        ParametrizedQuery::set($query, [$id_section, $id_expert]);
    }


    /**
     * Предназначен для установки флага, что работа по подготовке раздела не закочена (возобновлена)
     *
     * @param int $id_section id раздела
     * @param int $id_expert id эксперта
     * @throws DataBaseEx
     */
    static public function unsetSectionPreparationFinishedByIdSectionAndIdExpert(int $id_section, int $id_expert): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_section_preparation_finished`='0'
                  WHERE `id_section`=? AND `id_expert`=?";
        ParametrizedQuery::set($query, [$id_section, $id_expert]);
    }
}

