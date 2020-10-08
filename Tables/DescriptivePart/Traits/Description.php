<?php


namespace Tables\DescriptivePart\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait Description
{

    /**
     * Предназначен для создания записи в таблице описания описательной части
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @param string $description описание
     * @return int
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_author, string $description): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_author`, `description`, `date_creation`)
                  VALUES
                     (?, ?, ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_main_document, $id_author, $description]);
    }


    /**
     * Предназначен для получения ассоциативных массивов описаний описательной части
     * по id главного документа
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAllAssocByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `{$table}`.`id`, 
                         `{$table}`.`id_main_document`,
                         `user`.`id` as `author_id`,
                         `user`.`last_name`,
	                     `user`.`first_name`,
                         `user`.`middle_name`,
                         `{$table}`.`description`
                  FROM `{$table}`
                  JOIN `user`
                     ON (`{$table}`.`id_author`=`user`.`id`)
                  WHERE `{$table}`.`id_main_document`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для удаления записи в таблице описания описательной части
     * по id главного документа и id автора
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @throws DataBaseEx
     */
    static public function deleteByIdMainDocumentAndIdAuthor(int $id_main_document, int $id_author): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `id_author`=?";
        ParametrizedQuery::set($query, [$id_main_document, $id_author]);
    }
}

