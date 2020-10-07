<?php


namespace Tables\LoggingActions\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;



/**
 * Реализует интерфейс {@see \Tables\LoggingActions\Interfaces\LogActionTable}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 *
 *
 */
trait LogActionTable
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\LoggingActions\Interfaces\LogActionTable::create()}
     *
     * @param int $id_main_document
     * @param int $id_action
     * @param int $id_author
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        int $id_action,
        int $id_author
    ): int {

        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_action`, `id_author`, `date_creation`)
                  VALUES
                     (?, ?, ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query,  [$id_main_document, $id_action, $id_author]);
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\LoggingActions\Interfaces\LogActionTable::checkExistByIdMainDocumentAndIdAction()}
     *
     * @param int $id_main_document
     * @param int $id_action
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistByIdMainDocumentAndIdAction(int $id_main_document, int $id_action): bool
    {
        $table = self::$tableName;

        $query = "SELECT COUNT(*)>0
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `id_action`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_main_document, $id_action])[0];
    }

    /**
     * Реализация метода интерфейса
     * {@see \Tables\LoggingActions\Interfaces\LogActionTable::checkExistByIdMainDocumentAndActionCallbackName()}
     *
     * @param int $id_main_document
     * @param string $callback_name
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistByIdMainDocumentAndActionCallbackName(int $id_main_document, string $callback_name): bool
    {
        $table = self::$tableName;
        $actionTable = self::$actionTableName;

        $query = "SELECT COUNT(*)>0
                  FROM `{$table}`
                  JOIN (`{$actionTable}`)
                     ON (`{$table}`.`id_action`=`{$actionTable}`.`id`)
                  WHERE `{$table}`.`id_main_document`=? AND `{$actionTable}`.`callback_name`=?";

        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_main_document, $callback_name])[0];
    }
}