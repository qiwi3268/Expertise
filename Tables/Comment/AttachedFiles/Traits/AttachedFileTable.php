<?php


namespace Tables\Comment\AttachedFiles\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Tables\CommonTraits\deleteById as deleteByIdTrait;


/**
 * Реализует общие методы для таблиц прикрепленных файлов к замечанию
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait AttachedFileTable
{
    use deleteByIdTrait;


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


    /**
     * Предназначен для удаления записи из таблицы прикрепленных файлов к замечанию по id файла
     *
     * @param int $id_file id файла
     * @throws DataBaseEx
     */
    static public function deleteByIdFile(int $id_file): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id_file`=?";
        ParametrizedQuery::set($query, [$id_file]);
    }


    /**
     * Предназначен для удаления всех записей из таблицы прикрепленных файлов по id замечания
     *
     * @param int $id_main_document id главного документа
     * @throws DataBaseEx
     */
    static public function deleteAllByIdMainDocument(int $id_main_document): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id_main_document`=?";
        ParametrizedQuery::set($query, [$id_main_document]);
    }


    /**
     * Предназначен для получения простого массива id записей прикрепленных файлов по id замечания
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdsByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `id`
                  FROM `{$table}`
                  WHERE `id_main_document`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для получения простого массива id прикрепленных файлов по id замечания
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> индексный массив, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdFilesByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `id_file`
                  FROM `{$table}`
                  WHERE `id_main_document`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для получения id записи по id файла в таблице приклепленных файлов к замечанию
     *
     * @param int $id_file id файла
     * @return int|null <b>int</b> id записи по id файла, если она существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getIdByIdFile(int $id_file): ?int
    {
        $table = self::$tableName;

        $query = "SELECT `id`
                  FROM `{$table}`
                  WHERE `id_file`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_file]);
        return $result ? $result[0] : null;
    }
}