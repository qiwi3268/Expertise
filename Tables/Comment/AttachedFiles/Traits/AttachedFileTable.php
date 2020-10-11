<?php


namespace Tables\Comment\AttachedFiles\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует общие методы для таблиц прикрепленных файлов к замечанию
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait  AttachedFileTable
{

    /**
     * Предназначен для создания записи в таблице приклепленных файлов к замечанию
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @param int $id_file id файла
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        int $id_author,
        int $id_file
    ): int {

        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_author`, `id_file`, `date_creation`)
                  VALUES (?, ?, ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_main_document, $id_author, $id_file]);
    }
}