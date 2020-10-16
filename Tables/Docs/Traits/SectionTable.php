<?php


namespace Tables\Docs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;



/**
 * Реализует общие методы для таблиц документа "Раздел"
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 * - mainBlock341TableName с именем таблицы блоков по 341 приказу
 *
 */
trait SectionTable
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int $id_main_block
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_main_block): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `id_main_block`, `id_stage`, `responsible_type`,`date_creation`)
                  VALUES
                    (?, ?, 1, 'type_4', UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_main_document, $id_main_block]);
    }


    /**
     * Предназначен для получения name и short_name блока из 341 приказа по id записи раздела
     *
     * @param int $id id записи
     * @return array
     * @throws DataBaseEx
     */
    static public function getNameAndShortNameMainBlockById(int $id): array
    {
        $table = self::$tableName;
        $mainBlock341Table = self::$mainBlock341TableName;

        $query = "SELECT `{$mainBlock341Table}`.`name`,
                         `{$mainBlock341Table}`.`short_name`
                  FROM `{$table}`
                  JOIN (`{$mainBlock341Table}`)
                     ON (`{$table}`.`id_main_block` = `{$mainBlock341Table}`.`id`)
                  WHERE `{$table}`.`id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id])[0];
    }
}

