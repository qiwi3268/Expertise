<?php


namespace Tables\ActionsHistory;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'history_action_application'</i>
 *
 */
class application
{

    static private string $tableName = 'history_action_application';

    /**
     * Предназначен для создания записи в таблице истории действий к документу
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @param int $id_action id действия
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        int $id_author,
        int $id_action
    ): int {

        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_author`, `id_action`, `date_creation`)
                  VALUES
                     (?, ?, ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query,  [$id_main_document, $id_author, $id_action]);
    }


    /**
     * Предназначен для проверки существования записи по id главного документа и id действия
     *
     * @param int $id_main_document id главного документа
     * @param int $id_action id действия
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     * @throws DataBaseEx
     */
    static public function checkExistByIdMainDocumentAndIdAction(int $id_main_document, int $id_action): bool
    {
        $table = self::$tableName;

        $query = "SELECT count(*)>0
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `id_action`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_main_document, $id_action])[0];
    }
}