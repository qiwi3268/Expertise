<?php


namespace Tables\Docs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Docs\Interfaces\Document}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 * - stageTableName с именем таблицы стадий для данного документа
 *
 */
trait Document
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Docs\Interfaces\Document::getNameStageById()}
     *
     * @param int $id
     * @return string
     * @throws DataBaseEx
     */
    static public function getNameStageById(int $id): string
    {
        $table = self::$tableName;
        $stageTable = self::$stageTableName;

        $query = "SELECT `{$stageTable}`.`name`
                  FROM `{$table}`
                  JOIN `{$stageTable}`
                     ON (`{$table}`.`id_stage`=`{$stageTable}`.`id`)
                  WHERE `{$table}`.`id`=?";
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Docs\Interfaces\Document::updateIdStageById()}
     *
     * @param int $id_stage
     * @param int $id
     * @throws DataBaseEx
     */
    static public function updateIdStageById(int $id_stage, int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `id_stage`=?
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id_stage, $id]);
    }
}

