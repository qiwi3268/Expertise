<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'expertise_subject'</i>
 *
 */
final class expertise_subject
{

    /**
     * Предназначен для создания записи предмета экспертизы к заявлению
     *
     * @param int $id_application id заявления
     * @param int $id_expertise_subject id предмета экспертизы из справочника
     * @throws DataBaseEx
     */
    static public function create(int $id_application, int $id_expertise_subject): void
    {
        $query = "INSERT INTO `expertise_subject`
                    (`id_application`, `id_expertise_subject`)
                  VALUES
                    (?, ?)";
        ParametrizedQuery::set($query, [$id_application, $id_expertise_subject]);
    }


    /**
     * Предназначен для получения простого массива id предметов экспертизы по id заявления
     *
     * @param int $id_application id заявления
     * @return array|null <b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdsByIdApplication(int $id_application): ?array
    {
        $query = "SELECT `id_expertise_subject` AS `id`
                  FROM `expertise_subject`
                  WHERE `id_application`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_application]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для удаления записи предмета экспертизы по id заявления
     *
     * @param int $id_application id заявления
     * @param int $id_expertise_subject id предмета экспертизы из справочника
     * @throws DataBaseEx
     */
    static public function delete(int $id_application, int $id_expertise_subject): void
    {
        $query = "DELETE
                  FROM `expertise_subject`
                  WHERE `id_application`=? AND `id_expertise_subject`=?";
        ParametrizedQuery::set($query, [$id_application, $id_expertise_subject]);
    }
}